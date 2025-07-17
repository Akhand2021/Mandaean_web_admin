{{-- resources/views/admin/terms/action-buttons.blade.php --}}
<a href="{{ $editUrl }}" class="btn btn-sm btn-warning">Edit</a>

<form method="POST" action="{{ $deleteUrl }}" style="display:inline-block;"
    onsubmit="return confirm('Delete this entry?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>
