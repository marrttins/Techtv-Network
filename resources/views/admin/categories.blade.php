@extends('layouts.admin')

@section('header_title', 'Categories & Menus')

@section('admin_content')

{{-- Flash Messages --}}
@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem; box-shadow: 0 2px 6px rgba(22, 101, 52, 0.05);">
        <span style="font-size: 1.2rem;">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem;">
        <span style="font-size: 1.2rem;">⚠️</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; font-size: 0.9rem;">
        <div style="font-weight: 700; margin-bottom: 0.35rem;">Please check the following errors:</div>
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: start;">

    <!-- ========================================== -->
    <!-- 1. CATEGORIES SECTION                      -->
    <!-- ========================================== -->
    <div>
        <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.35rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">
                Article Categories
            </h2>
            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
                Manage categories used for organizing content across TechTV.
            </p>
        </div>
        
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
            <!-- Categories List Table -->
            <div style="background-color: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
                <table class="table-admin" style="margin-top: 0; margin-bottom: 0; border: none; box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Slug / Path</th>
                            <th>Articles</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td style="font-weight: 700; color: #1e293b;">
                                    <a href="{{ url('/category/' . $cat->slug) }}" target="_blank" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='inherit'">
                                        {{ $cat->name }} ↗
                                    </a>
                                </td>
                                <td>
                                    <code style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.82rem; color: #475569;">{{ $cat->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge-status" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                                        {{ $cat->posts_count }} posts
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <form action="{{ url('/admin/categories/' . $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete category \'{{ addslashes($cat->name) }}\'? All linked posts will remain safe.');" style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--admin-text-muted); padding: 2.5rem;">No categories created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Create Category Form -->
            <div style="background-color: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
                <div style="border-left: 3px solid #0B193C; padding-left: 0.6rem; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.05rem; font-family: 'Poppins', sans-serif; font-weight: 700; margin: 0; color: #1e293b;">
                        New Category
                    </h3>
                </div>
                <form action="{{ url('/admin/categories') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 700; color: #334155;">Category Name *</label>
                        <input class="input-field" type="text" name="name" placeholder="e.g. Artificial Intelligence" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 700; color: #334155;">Description (Optional)</label>
                        <textarea class="input-field" name="description" rows="3" placeholder="Brief description of this category..."></textarea>
                    </div>
                    <button class="btn-admin-cta" type="submit" style="width: 100%; justify-content: center;">
                        + Create Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    <hr style="border: none; border-top: 1px solid var(--admin-border);">

    <!-- ========================================== -->
    <!-- 2. MENUS & NAVIGATION SECTION              -->
    <!-- ========================================== -->
    <div>
        <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.35rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">
                Navigation Menus
            </h2>
            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
                Configure main navigation bars, dropdowns, and footer links.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
            
            <!-- Left: Menu Selector & Creator -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- Select Menu Card -->
                <div style="background-color: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
                    <h3 style="font-size: 1.05rem; font-family: 'Poppins', sans-serif; font-weight: 700; margin: 0 0 1rem 0; color: #1e293b;">
                        Select Menu to Edit
                    </h3>
                    <form method="GET" action="{{ url('/admin/categories') }}" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <select name="menu_id" class="input-field" onchange="this.form.submit()" style="font-weight: 600;">
                            @if($menus->isEmpty())
                                <option value="">No menus created</option>
                            @else
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}" {{ $activeMenuId == $menu->id ? 'selected' : '' }}>
                                        {{ $menu->name }} ({{ strtoupper($menu->location ?: 'Custom') }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </form>
                    
                    @if($activeMenu)
                        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--admin-border);">
                            <form action="{{ url('/admin/menus/' . $activeMenu->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the entire menu \'{{ addslashes($activeMenu->name) }}\' and all its links?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" style="width: 100%; justify-content: center; padding: 0.6rem 1rem;">
                                    🗑️ Delete This Menu
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Create New Menu Card -->
                <div style="background-color: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
                    <h3 style="font-size: 1.05rem; font-family: 'Poppins', sans-serif; font-weight: 700; margin: 0 0 1rem 0; color: #1e293b;">
                        Create New Menu
                    </h3>
                    <form action="{{ url('/admin/menus') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.15rem;">
                        @csrf
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 700; color: #334155;">Menu Name *</label>
                            <input class="input-field" type="text" name="name" placeholder="e.g. Header Main Menu" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 700; color: #334155;">Theme Location</label>
                            <select name="location" class="input-field">
                                <option value="header">Header Navigation</option>
                                <option value="footer">Footer Navigation</option>
                            </select>
                        </div>
                        <button class="btn-admin-cta" type="submit" style="width: 100%; justify-content: center;">
                            + Create Menu
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Menu Items Manager -->
            <div style="background-color: #ffffff; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.75rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);">
                @if($activeMenu)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Currently Editing</span>
                            <h3 style="font-size: 1.3rem; font-family: 'Poppins', sans-serif; font-weight: 800; color: #1e293b; margin: 0;">
                                {{ $activeMenu->name }}
                            </h3>
                        </div>
                        <button type="button" class="btn-admin-cta" onclick="saveMenuOrder()" id="save-order-btn" style="padding: 0.55rem 1.25rem;">
                            💾 Save Order
                        </button>
                    </div>

                    <!-- Add Item Form -->
                    <div style="background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1.25rem; margin-bottom: 2rem;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0 0 1rem 0;">+ Add Menu Item</h4>
                        <form action="{{ url('/admin/menus/' . $activeMenu->id . '/items') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                            @csrf
                            <div style="flex: 1; min-width: 160px;">
                                <label class="form-label" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Link Label *</label>
                                <input class="input-field" type="text" name="label" placeholder="e.g. Artificial Intelligence" required>
                            </div>
                            <div style="flex: 2; min-width: 260px;">
                                <label class="form-label" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Destination URL *</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <select class="input-field" onchange="if(this.value){document.getElementById('custom_url').value = this.value;}" style="width: 150px; font-size: 0.82rem;">
                                        <option value="">-- Quick Pick --</option>
                                        <option value="/">🏠 Home (/)</option>
                                        <option value="/about">About Us (/about)</option>
                                        <option value="/advertise">Advertise (/advertise)</option>
                                        <option value="/contact">Contact (/contact)</option>
                                        @foreach($categories as $cat)
                                            <option value="/category/{{ $cat->slug }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <input class="input-field" type="text" name="url" id="custom_url" placeholder="/path or https://..." required style="flex: 1;">
                                </div>
                            </div>
                            <div style="flex: 1; min-width: 160px;">
                                <label class="form-label" style="font-size: 0.8rem; font-weight: 700; color: #475569;">Parent (Dropdown)</label>
                                <select name="parent_id" class="input-field">
                                    <option value="">None (Top Level)</option>
                                    @foreach($activeMenu->items->whereNull('parent_id') as $parentItem)
                                        <option value="{{ $parentItem->id }}">{{ $parentItem->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn-admin-cta" type="submit" style="padding: 0.75rem 1.25rem; white-space: nowrap;">
                                Add Link
                            </button>
                        </form>
                    </div>

                    <!-- Items List -->
                    @if($activeMenu->items->isEmpty())
                        <div style="text-align: center; color: var(--admin-text-muted); padding: 3rem 1.5rem; background: #f8fafc; border-radius: 8px; border: 1px dashed var(--admin-border);">
                            <p style="margin: 0; font-weight: 600;">No items added to this menu yet.</p>
                            <small style="color: #94a3b8;">Use the form above to add navigation links and dropdown sub-items.</small>
                        </div>
                    @else
                        <div id="menu-items-list" style="display: flex; flex-direction: column; gap: 0.65rem;">
                            @foreach($activeMenu->items->whereNull('parent_id')->sortBy('order') as $item)
                                <!-- Top-Level Item -->
                                <div class="menu-item-row" data-id="{{ $item->id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.15rem; background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; transition: border-color 0.2s;">
                                    <div style="display: flex; align-items: center; gap: 1rem; min-width: 0;">
                                        <span style="cursor: grab; color: #94a3b8; font-size: 1.1rem;" title="Drag order">☰</span>
                                        <span style="font-weight: 700; color: #1e293b; font-size: 0.92rem;">{{ $item->label }}</span>
                                        <span style="color: #64748b; font-size: 0.82rem; font-family: monospace; background: #e2e8f0; padding: 0.15rem 0.45rem; border-radius: 4px;">{{ $item->url }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <input type="number" class="item-order-input" value="{{ $item->order }}" style="width: 55px; padding: 0.35rem; border: 1px solid var(--admin-border); border-radius: 6px; text-align: center; font-weight: 600;" title="Order Index">
                                        <form action="{{ url('/admin/menu-items/' . $item->id . '/delete') }}" method="POST" onsubmit="return confirm('Remove menu item \'{{ addslashes($item->label) }}\' and any child links?');" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="menu_id" value="{{ $activeMenu->id }}">
                                            <button type="submit" class="btn-action btn-delete" style="padding: 0.35rem 0.65rem; font-size: 0.8rem; cursor: pointer;">
                                                🗑️ Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Child Dropdown Items -->
                                @foreach($activeMenu->items->where('parent_id', $item->id)->sortBy('order') as $child)
                                    <div class="menu-item-row" data-id="{{ $child->id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 1rem; background: #ffffff; border: 1px dashed var(--admin-border); border-left: 3px solid #0284c7; border-radius: 6px; margin-left: 2.25rem;">
                                        <div style="display: flex; align-items: center; gap: 0.85rem; min-width: 0;">
                                            <span style="cursor: grab; color: #94a3b8;" title="Drag order">↳ ☰</span>
                                            <span style="font-weight: 600; color: #334155; font-size: 0.88rem;">{{ $child->label }}</span>
                                            <span style="color: #64748b; font-size: 0.78rem; font-family: monospace; background: #f1f5f9; padding: 0.1rem 0.4rem; border-radius: 4px;">{{ $child->url }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <input type="number" class="item-order-input" value="{{ $child->order }}" style="width: 55px; padding: 0.35rem; border: 1px solid var(--admin-border); border-radius: 6px; text-align: center; font-size: 0.85rem;" title="Order Index">
                                            <form action="{{ url('/admin/menu-items/' . $child->id . '/delete') }}" method="POST" onsubmit="return confirm('Remove sub-item \'{{ addslashes($child->label) }}\'?');" style="margin: 0; display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="menu_id" value="{{ $activeMenu->id }}">
                                                <button type="submit" class="btn-action btn-delete" style="padding: 0.35rem 0.65rem; font-size: 0.8rem; cursor: pointer;">
                                                    🗑️ Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                @else
                    <div style="text-align: center; color: var(--admin-text-muted); padding: 4rem 2rem;">
                        <span style="font-size: 3rem; display: block; margin-bottom: 1rem;">🧭</span>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">No Menu Selected</h4>
                        <p style="color: #64748b; font-size: 0.9rem;">Select or create a menu from the left panel to manage its navigation links.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('admin_scripts')
<script>
function removeItem(itemId, label) {
    if (!confirm('Are you sure you want to remove "' + label + '"?')) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("/admin/menu-items") }}/' + itemId + '/delete';
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrf);

    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);

    const menuIdInput = document.createElement('input');
    menuIdInput.type = 'hidden';
    menuIdInput.name = 'menu_id';
    menuIdInput.value = '{{ $activeMenu ? $activeMenu->id : "" }}';
    form.appendChild(menuIdInput);

    document.body.appendChild(form);
    form.submit();
}

function saveMenuOrder() {
    const rows = document.querySelectorAll('.menu-item-row');
    const items = [];
    rows.forEach(row => {
        items.push({
            id: row.getAttribute('data-id'),
            order: row.querySelector('.item-order-input').value
        });
    });

    const btn = document.getElementById('save-order-btn');
    btn.textContent = 'Saving...';
    btn.disabled = true;

    fetch('{{ url("/admin/menu-items/reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ items: items })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            btn.textContent = '✓ Order Saved!';
            btn.style.background = '#16a34a';
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            alert('Error saving order.');
            btn.textContent = '💾 Save Order';
            btn.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        alert('Network error while saving order.');
        btn.textContent = '💾 Save Order';
        btn.disabled = false;
    });
}
</script>
@endsection
