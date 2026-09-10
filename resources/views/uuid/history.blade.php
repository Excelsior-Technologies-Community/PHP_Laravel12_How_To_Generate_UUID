<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UUID Generation History</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="fw-bold">
                UUID Generation History
            </h1>

            <p class="text-muted">
                View, search and filter generated UUIDs.
            </p>

        </div>

        <a
            href="{{ route('uuid.dashboard') }}"
            class="btn btn-dark">

            Dashboard

        </a>

    </div>


    <!-- Success -->

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <!-- Filters -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('uuid.history') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search UUID or type">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            UUID Version
                        </label>

                        <select
                            name="version"
                            class="form-select">

                            <option value="">
                                All Versions
                            </option>

                            <option
                                value="UUID v4"
                                @selected(request('version') === 'UUID v4')>

                                UUID v4

                            </option>

                            <option
                                value="Ordered UUID"
                                @selected(request('version') === 'Ordered UUID')>

                                Ordered UUID

                            </option>

                            <option
                                value="UUID v7"
                                @selected(request('version') === 'UUID v7')>

                                UUID v7

                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Date
                        </label>

                        <input
                            type="date"
                            name="date"
                            class="form-control"
                            value="{{ request('date') }}">

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- History Table -->

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Generated UUIDs
                </h4>


                @if($histories->count() > 0)

                    <form
                        method="POST"
                        action="{{ route('uuid.history.clear') }}"
                        onsubmit="return confirm('Are you sure you want to delete all UUID history?')">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-sm btn-danger">

                            Clear All History

                        </button>

                    </form>

                @endif

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="table-dark">

                <tr>

                    <th>#</th>

                    <th>UUID</th>

                    <th>Type</th>

                    <th>Version</th>

                    <th>Generated At</th>

                    <th>Action</th>

                </tr>

                </thead>


                <tbody>

                @forelse($histories as $history)

                    <tr>

                        <td>
                            {{ $history->id }}
                        </td>

                        <td>

                            <div class="input-group">

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="{{ $history->uuid }}"
                                    readonly>

                                <button
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="copyText('{{ $history->uuid }}')">

                                    Copy

                                </button>

                            </div>

                        </td>


                        <td>

                            <span class="badge bg-primary">

                                {{ $history->type }}

                            </span>

                        </td>


                        <td>

                            {{ $history->version }}

                        </td>


                        <td>

                            {{ $history->generated_at->format('d M Y, h:i A') }}

                        </td>


                        <td>

                            <form
                                method="POST"
                                action="{{ route('uuid.history.delete', $history) }}"
                                onsubmit="return confirm('Delete this UUID history?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-outline-danger">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5 text-muted">

                            No UUID history found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($histories->hasPages())

            <div class="card-footer bg-white">

                {{ $histories->links() }}

            </div>

        @endif

    </div>

</div>


<script>

function copyText(value)
{
    navigator.clipboard.writeText(value);

    alert('UUID copied to clipboard.');
}

</script>

</body>

</html>