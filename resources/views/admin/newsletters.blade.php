@extends('layouts.admin')

@section('header_title', 'Newsletter Subscribers')

@section('admin_content')
<div>
    <h3 style="font-size: 1.15rem; font-family: 'Poppins', sans-serif; font-weight: 800; margin-bottom: 1rem; color: #1e293b;">Subscriber List</h3>
    
    <div class="table-responsive">
        <table class="table-admin" style="min-width: 500px;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Subscribed At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                    <tr>
                        <td style="font-weight: 600;">{{ $sub->name ?: 'N/A' }}</td>
                        <td style="font-weight: 600; color: #0284c7;">{{ $sub->email }}</td>
                        <td>
                            <span class="badge-status badge-publish">
                                {{ $sub->status }}
                            </span>
                        </td>
                        <td style="white-space: nowrap; color: #64748b; font-size: 0.85rem;">{{ $sub->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 3rem;">No subscribers yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
