<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UUID Generation History</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body class="bg-light">


<div class="container py-5">


    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="fw-bold">
                UUID Generation History
            </h1>

            <p class="text-muted">
                Search, filter, sort and export UUID history.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('uuid.dashboard') }}"
                class="btn btn-dark">

                Dashboard

            </a>

            <a
                href="{{ route('uuid.bulk') }}"
                class="btn btn-primary">

                Bulk Generator

            </a>

        </div>

    </div>


    <!-- SUCCESS -->

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <!-- FILTERS -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('uuid.history') }}">


                <div class="row g-3">


                    <!-- SEARCH -->

                    <div class="col-md-4">

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


                    <!-- VERSION -->

                    <div class="col-md-2">

                        <label class="form-label">
                            Version
                        </label>

                        <select
                            name="version"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="UUID v4"
                                @selected(
                                    request('version') === 'UUID v4'
                                )>

                                UUID v4

                            </option>

                            <option
                                value="Ordered UUID"
                                @selected(
                                    request('version') === 'Ordered UUID'
                                )>

                                Ordered UUID

                            </option>

                            <option
                                value="UUID v7"
                                @selected(
                                    request('version') === 'UUID v7'
                                )>

                                UUID v7

                            </option>

                        </select>

                    </div>


                    <!-- FROM DATE -->

                    <div class="col-md-2">

                        <label class="form-label">
                            From
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}">

                    </div>


                    <!-- TO DATE -->

                    <div class="col-md-2">

                        <label class="form-label">
                            To
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}">

                    </div>


                    <!-- PER PAGE -->

                    <div class="col-md-2">

                        <label class="form-label">
                            Per Page
                        </label>

                        <select
                            name="per_page"
                            class="form-select">

                            @foreach([5,10, 25, 50, 100] as $size)

                                <option
                                    value="{{ $size }}"
                                    @selected(
                                        (int)request('per_page', 5)
                                        === $size
                                    )>

                                    {{ $size }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- SORT -->

                    <div class="col-md-3">

                        <label class="form-label">
                            Sort By
                        </label>

                        <select
                            name="sort"
                            class="form-select">

                            <option
                                value="generated_at"
                                @selected(
                                    request('sort') === 'generated_at'
                                    || !request('sort')
                                )>

                                Generated At

                            </option>

                            <option
                                value="id"
                                @selected(
                                    request('sort') === 'id'
                                )>

                                ID

                            </option>

                            <option
                                value="uuid"
                                @selected(
                                    request('sort') === 'uuid'
                                )>

                                UUID

                            </option>

                            <option
                                value="type"
                                @selected(
                                    request('sort') === 'type'
                                )>

                                Type

                            </option>

                            <option
                                value="version"
                                @selected(
                                    request('sort') === 'version'
                                )>

                                Version

                            </option>

                        </select>

                    </div>


                    <!-- DIRECTION -->

                    <div class="col-md-2">

                        <label class="form-label">
                            Direction
                        </label>

                        <select
                            name="direction"
                            class="form-select">

                            <option
                                value="desc"
                                @selected(
                                    request('direction', 'desc') === 'desc'
                                )>

                                Descending

                            </option>

                            <option
                                value="asc"
                                @selected(
                                    request('direction') === 'asc'
                                )>

                                Ascending

                            </option>

                        </select>

                    </div>


                    <!-- SEARCH -->

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Apply Filters

                        </button>

                    </div>


                    <!-- CLEAR -->

                    <div class="col-md-2 d-flex align-items-end">

                        <a
                            href="{{ route('uuid.history') }}"
                            class="btn btn-outline-secondary w-100">

                            Clear

                        </a>

                    </div>


                </div>

            </form>

        </div>

    </div>


    <!-- EXPORT -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        Export History
                    </h5>

                    <small class="text-muted">
                        Export your UUID records.
                    </small>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route(
                            'uuid.history.export.csv',
                            request()->query()
                        ) }}"
                        class="btn btn-success">

                        Export CSV

                    </a>


                    <a
                        href="{{ route(
                            'uuid.history.export.json'
                        ) }}"
                        class="btn btn-warning">

                        Export JSON

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- TABLE -->

    <div class="card shadow-sm border-0">


        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">


                <h4 class="mb-0">
                    Generated UUIDs
                </h4>


                <div class="d-flex gap-2">


                    @if($histories->count() > 0)

                        <form
                            method="POST"
                            action="{{ route('uuid.history.clear') }}"
                            onsubmit="return confirm(
                                'Are you sure you want to delete all UUID history?'
                            )">

                            @csrf

                            @method('DELETE')

                            <button
                                class="btn btn-sm btn-danger">

                                Clear All

                            </button>

                        </form>

                    @endif


                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover mb-0">


                <thead class="table-dark">

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        UUID
                    </th>

                    <th>
                        Type
                    </th>

                    <th>
                        Version
                    </th>

                    <th>
                        Generated At
                    </th>

                    <th>
                        Actions
                    </th>

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
                                    onclick="copyText(
                                        '{{ $history->uuid }}'
                                    )">

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

                            {{ $history->generated_at->format(
                                'd M Y, h:i A'
                            ) }}

                        </td>


                        <td>


                            <div class="d-flex gap-1">


                                <!-- REGENERATE -->

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'uuid.history.regenerate',
                                        $history
                                    ) }}">

                                    @csrf

                                    <button
                                        class="btn btn-sm btn-outline-success">

                                        Regenerate

                                    </button>

                                </form>


                                <!-- DELETE -->

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'uuid.history.delete',
                                        $history
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Delete this UUID history?'
                                    )">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-outline-danger">

                                        Delete

                                    </button>

                                </form>


                            </div>

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


        <!-- PAGINATION -->

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

    alert(
        'UUID copied to clipboard.'
    );
}

</script>


</body>

</html>