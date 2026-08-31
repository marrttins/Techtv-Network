@extends('layouts.admin')

@section('header_title', 'Newsletter Subscribers')

@section('admin_content')
<div>
    <h3 style="font-size: 1.25rem; font-family: 'Outfit'; margin-bottom: 1rem;">Subscriber List</h3>
    
    <table class="table-admin" style="margin-top: 0;">
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
                    <td>{{ $sub->name ?: 'N/A' }}</td>
                    <td style="font-weight: 600;">{{ $sub->email }}</td>
                    <td>
                        <span class="badge-status badge-publish">
                            {{ $sub->status }}
                        </span>
                    </td>
                    <td>{{ $sub->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-muted);">No subscribers yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
