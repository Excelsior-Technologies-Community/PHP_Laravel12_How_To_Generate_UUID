<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Bulk UUID Generator</title>

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
                Bulk UUID Generator
            </h1>

            <p class="text-muted mb-0">
                Generate multiple UUIDs at once and export them as CSV.
            </p>

        </div>

        <div>

            <a
                href="{{ route('uuid.dashboard') }}"
                class="btn btn-dark">

                Dashboard

            </a>

            <a
                href="{{ route('uuid.history') }}"
                class="btn btn-outline-dark">

                History

            </a>

        </div>

    </div>


    <!-- Success Message -->

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <!-- Validation Errors -->

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- Generator Form -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h4 class="mb-0">
                Generate UUIDs
            </h4>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('uuid.bulk.generate') }}">

                @csrf

                <div class="row g-4">

                    <!-- UUID Type -->

                    <div class="col-md-7">

                        <label class="form-label fw-semibold">

                            UUID Type

                        </label>

                        <select
                            name="type"
                            class="form-select"
                            required>

                            <option value="UUID v4"
                                @selected(($type ?? '') === 'UUID v4')>

                                UUID v4 - Random

                            </option>

                            <option value="Ordered UUID"
                                @selected(($type ?? '') === 'Ordered UUID')>

                                Ordered UUID - Time-based

                            </option>

                            <option value="UUID v7"
                                @selected(($type ?? '') === 'UUID v7')>

                                UUID v7 - Modern Time-based

                            </option>

                        </select>

                    </div>


                    <!-- Quantity -->

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">

                            Number of UUIDs

                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            value="{{ $quantity ?? 10 }}"
                            min="1"
                            max="100"
                            required>

                        <small class="text-muted">

                            Maximum 100 UUIDs per request.

                        </small>

                    </div>

                </div>


                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Generate UUIDs

                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- Generated Results -->

    @isset($generatedUuids)

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="mb-1">
                            Generated UUIDs
                        </h4>

                        <small class="text-muted">

                            {{ $quantity }}
                            {{ $type }} generated successfully.

                        </small>

                    </div>


                    <div>

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="copyAllUuids()">

                            Copy All

                        </button>


                        <form
                            method="POST"
                            action="{{ route('uuid.bulk.export') }}"
                            class="d-inline">

                            @csrf

                            <input
                                type="hidden"
                                name="type"
                                value="{{ $type }}">

                            <textarea
                                name="uuids"
                                id="csvUuids"
                                class="d-none">{{ implode("\n", $generatedUuids) }}</textarea>

                            <button
                                type="submit"
                                class="btn btn-success">

                                Export CSV

                            </button>

                        </form>

                    </div>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-dark">

                        <tr>

                            <th width="80">
                                #
                            </th>

                            <th>
                                UUID
                            </th>

                            <th>
                                Type
                            </th>

                            <th width="120">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($generatedUuids as $index => $uuid)

                            <tr>

                                <td>

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    <code>
                                        {{ $uuid }}
                                    </code>

                                </td>


                                <td>

                                    <span class="badge bg-primary">

                                        {{ $type }}

                                    </span>

                                </td>


                                <td>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-dark"
                                        onclick="copyUuid('{{ $uuid }}')">

                                        Copy

                                    </button>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="card-footer bg-white">

                <strong>
                    Total Generated:
                </strong>

                {{ count($generatedUuids) }}

            </div>

        </div>

    @endisset

</div>


<script>

function copyUuid(uuid)
{
    navigator.clipboard.writeText(uuid);

    alert('UUID copied to clipboard.');
}


function copyAllUuids()
{
    const uuids = @json($generatedUuids ?? []);

    if (uuids.length === 0) {

        return;

    }

    navigator.clipboard.writeText(
        uuids.join('\n')
    );

    alert(
        uuids.length +
        ' UUIDs copied to clipboard.'
    );
}

</script>

</body>

</html>