<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminMediaController extends Controller
{
    private string $uploadsDir;

    public function __construct()
    {
        $this->uploadsDir = public_path('uploads');
    }

    /* ----------------------------------------------------------------
     |  INDEX — Media Library grid with filter / search / pagination
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $type    = $request->input('type', 'all');
        $search  = (string) $request->input('search', '');
        $page    = max(1, (int) $request->input('page', 1));
        $perPage = 48;

        if (!File::exists($this->uploadsDir)) {
            File::makeDirectory($this->uploadsDir, 0755, true);
        }

        $allFiles  = $this->collectFiles($type, $search);
        $total     = count($allFiles);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page      = min($page, $totalPages);
        $files     = array_slice($allFiles, ($page - 1) * $perPage, $perPage);

        // Counts for filter tabs
        $countAll    = count($this->collectFiles('all', $search));
        $countImages = count($this->collectFiles('images', $search));
        $countDocs   = count($this->collectFiles('documents', $search));

        return view('admin.media', compact(
            'files', 'total', 'page', 'totalPages',
            'type', 'search', 'perPage',
            'countAll', 'countImages', 'countDocs'
        ));
    }

    public function api(Request $request)
    {
        $search = (string) $request->input('search', '');
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 24;
        
        $allFiles = $this->collectFiles('images', $search);
        
        // Sort by modified desc
        usort($allFiles, function($a, $b) {
            return $b['modified'] <=> $a['modified'];
        });

        $total = count($allFiles);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $files = array_slice($allFiles, ($page - 1) * $perPage, $perPage);

        return response()->json([
            'images' => array_values($files),
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }

    /* ----------------------------------------------------------------
     |  COLLECT FILES — Recursive scan of uploads/ directory
     * ---------------------------------------------------------------- */
    private function collectFiles(string $type, string $search): array
    {
        $files = [];

        if (!is_dir($this->uploadsDir)) return $files;

        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'avif'];
        $docExts   = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'csv', 'mp4', 'mp3'];

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->uploadsDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if (!$file->isFile()) continue;

                $ext      = strtolower($file->getExtension());
                $filename = $file->getFilename();

                if (str_starts_with($filename, '.')) continue;
                // Skip auto-generated companion WebP files (e.g. photo.jpg.webp)
                if (preg_match('/\.(jpe?g|png|gif|bmp)\.webp$/i', $filename)) continue;
                if ($type === 'images'    && !in_array($ext, $imageExts)) continue;
                if ($type === 'documents' && !in_array($ext, $docExts))   continue;
                if ($search && stripos($filename, $search) === false)     continue;

                $relPath = str_replace(
                    $this->uploadsDir . DIRECTORY_SEPARATOR, '',
                    $file->getPathname()
                );
                $relPath = str_replace('\\', '/', $relPath);
                $dbPath  = 'uploads/' . $relPath;

                $files[] = [
                    'path'     => $dbPath,
                    'url'      => asset($dbPath),
                    'filename' => $filename,
                    'ext'      => $ext,
                    'size'     => $file->getSize(),
                    'modified' => $file->getMTime(),
                    'is_image' => in_array($ext, $imageExts) && $ext !== 'svg',
                    'is_svg'   => $ext === 'svg',
                    'relative' => $relPath,
                ];
            }
        } catch (\Exception $e) {
            // silently skip unreadable dirs
        }

        // Sort newest first
        usort($files, fn($a, $b) => $b['modified'] - $a['modified']);

        return $files;
    }

    /* ----------------------------------------------------------------
     |  UPLOAD — handles single or multi-file upload, converts to WebP
     * ---------------------------------------------------------------- */
    public function upload(Request $request)
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'required|file|max:51200', // 50 MB
        ]);

        $uploaded = [];
        $errors   = [];

        foreach ($request->file('files') as $file) {
            try {
                $uploaded[] = $this->processUpload($file);
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'uploaded' => $uploaded,
                'errors'   => $errors,
                'message'  => count($uploaded) . ' file(s) uploaded.',
            ]);
        }

        $msg = count($uploaded) . ' file(s) uploaded successfully.';
        if (!empty($errors)) {
            $msg .= ' Issues: ' . implode('; ', $errors);
        }

        return redirect()->route('admin.media.index')->with('success', $msg);
    }

    /* ----------------------------------------------------------------
     |  PROCESS UPLOAD — Convert image → WebP, save in year/month dir
     * ---------------------------------------------------------------- */
    private function processUpload($file): array
    {
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'avif'];
        $ext       = strtolower($file->getClientOriginalExtension());
        $baseName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName  = Str::slug($baseName) . '-' . time() . '-' . Str::random(6);

        // Organise by year/month
        $yearMonth = date('Y/m');
        $destDir   = $this->uploadsDir . '/' . $yearMonth;
        if (!File::exists($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        $isConvertible = in_array($ext, $imageExts) && extension_loaded('gd');

        if ($isConvertible) {
            $destFilename = $safeName . '.webp';
            $destPath     = $destDir . '/' . $destFilename;
            $ok           = $this->convertToWebP($file->getRealPath(), $destPath, 82);
            if (!$ok) {
                // Fallback: save original
                $destFilename = $safeName . '.' . $ext;
                $destPath     = $destDir . '/' . $destFilename;
                $file->move($destDir, $destFilename);
            }
        } else {
            $destFilename = $safeName . '.' . $ext;
            $file->move($destDir, $destFilename);
        }

        $dbPath = 'uploads/' . $yearMonth . '/' . $destFilename;

        return [
            'path'     => $dbPath,
            'url'      => asset($dbPath),
            'filename' => $destFilename,
        ];
    }

    /* ----------------------------------------------------------------
     |  CONVERT TO WEBP — GD-based image → WebP conversion
     * ---------------------------------------------------------------- */
    private function convertToWebP(string $src, string $dest, int $quality = 82): bool
    {
        $info = @getimagesize($src);
        if (!$info) return false;

        $image = match ($info['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($src),
            'image/png'  => @imagecreatefrompng($src),
            'image/gif'  => @imagecreatefromgif($src),
            'image/bmp'  => @imagecreatefrombmp($src),
            'image/webp' => @imagecreatefromwebp($src),
            'image/avif' => function_exists('imagecreatefromavif') ? @imagecreatefromavif($src) : null,
            default      => null,
        };

        if (!$image) return false;

        // Preserve alpha / transparency
        if (in_array($info['mime'], ['image/png', 'image/gif', 'image/webp'])) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        $ok = imagewebp($image, $dest, $quality);
        imagedestroy($image);

        return $ok;
    }

    /* ----------------------------------------------------------------
     |  DELETE — remove a single file from uploads/
     * ---------------------------------------------------------------- */
    public function destroy(Request $request)
    {
        $path     = $request->input('path', '');
        $fullPath = public_path($path);
        $realBase = realpath($this->uploadsDir);
        $realFile = realpath($fullPath);

        // Security: only allow deletion inside uploads/
        if (!$realFile || !$realBase || !str_starts_with($realFile, $realBase)) {
            return response()->json(['error' => 'Invalid file path.'], 403);
        }

        if (File::exists($fullPath)) {
            File::delete($fullPath);
            return response()->json(['success' => true, 'message' => 'File deleted.']);
        }

        return response()->json(['error' => 'File not found.'], 404);
    }


    /* ----------------------------------------------------------------
     |  HELPER — Human-readable file size
     * ---------------------------------------------------------------- */
    public static function humanSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
