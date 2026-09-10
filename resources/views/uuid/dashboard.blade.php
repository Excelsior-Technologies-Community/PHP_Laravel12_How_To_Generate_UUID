<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UUID Generator Dashboard</title>

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
                    UUID Generator
                </h1>

                <p class="text-muted mb-0">
                    Generate and manage Laravel UUIDs
                </p>
            </div>

            <div>

                <a
                    href="{{ route('uuid.bulk') }}"
                    class="btn btn-primary">
                    Bulk Generator
                </a>

                <a
                    href="{{ route('uuid.history') }}"
                    class="btn btn-dark">
                    UUID History
                </a>

                <a
                    href="{{ route('uuid.validator') }}"
                    class="btn btn-outline-primary">
                    UUID Validator
                </a>

            </div>

        </div>


        <!-- Statistics -->

        <div class="row g-4 mb-5">

            <div class="col-md-4 col-lg">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Total UUIDs
                        </h6>

                        <h2 class="fw-bold">
                            {{ $total }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-4 col-lg">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            UUID v4
                        </h6>

                        <h2 class="fw-bold">
                            {{ $uuidV4Count }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-4 col-lg">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Ordered UUID
                        </h6>

                        <h2 class="fw-bold">
                            {{ $orderedUuidCount }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-4 col-lg">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            UUID v7
                        </h6>

                        <h2 class="fw-bold">
                            {{ $uuidV7Count }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-4 col-lg">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Generated Today
                        </h6>

                        <h2 class="fw-bold">
                            {{ $todayCount }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>


        <!-- Generate UUID -->

        <div class="card shadow-sm border-0 mb-5">

            <div class="card-header bg-white">

                <h4 class="mb-0">
                    Generate UUID
                </h4>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <!-- UUID v4 -->

                    <div class="col-md-4">

                        <div class="border rounded p-4 h-100">

                            <h5>
                                UUID v4
                            </h5>

                            <p class="text-muted">
                                Random UUID
                            </p>

                            <button
                                class="btn btn-primary w-100"
                                onclick="generateUuid('/uuid', 'UUID v4')">

                                Generate UUID v4

                            </button>

                        </div>

                    </div>


                    <!-- Ordered -->

                    <div class="col-md-4">

                        <div class="border rounded p-4 h-100">

                            <h5>
                                Ordered UUID
                            </h5>

                            <p class="text-muted">
                                Time-based ordered UUID
                            </p>

                            <button
                                class="btn btn-success w-100"
                                onclick="generateUuid('/ordered-uuid', 'Ordered UUID')">

                                Generate Ordered UUID

                            </button>

                        </div>

                    </div>


                    <!-- UUID v7 -->

                    <div class="col-md-4">

                        <div class="border rounded p-4 h-100">

                            <h5>
                                UUID v7
                            </h5>

                            <p class="text-muted">
                                Modern time-ordered UUID
                            </p>

                            <button
                                class="btn btn-dark w-100"
                                onclick="generateUuid('/uuid7', 'UUID v7')">

                                Generate UUID v7

                            </button>

                        </div>

                    </div>

                </div>


                <!-- Result -->

                <div
                    id="result"
                    class="alert alert-success mt-4 d-none">

                    <strong id="resultType"></strong>

                    <div class="input-group mt-2">

                        <input
                            type="text"
                            id="generatedUuid"
                            class="form-control"
                            readonly>

                        <button
                            class="btn btn-outline-dark"
                            onclick="copyUuid()">

                            Copy

                        </button>

                    </div>

                </div>

            </div>

        </div>


        <!-- Recent UUIDs -->

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between">

                    <h4 class="mb-0">
                        Recent UUIDs
                    </h4>

                    <a
                        href="{{ route('uuid.history') }}"
                        class="btn btn-sm btn-outline-dark">

                        View All

                    </a>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>
                                <th>UUID</th>
                                <th>Type</th>
                                <th>Generated At</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($recentUuids as $item)

                            <tr>

                                <td>
                                    {{ $item->id }}
                                </td>

                                <td>
                                    <code>
                                        {{ $item->uuid }}
                                    </code>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $item->type }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->generated_at->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center py-4 text-muted">

                                    No UUIDs generated yet.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <script>
        async function generateUuid(url, type) {
            try {

                const response = await fetch(url);

                const data = await response.json();

                document
                    .getElementById('result')
                    .classList.remove('d-none');

                document
                    .getElementById('resultType')
                    .innerText = data.type;

                document
                    .getElementById('generatedUuid')
                    .value = data.uuid;

            } catch (error) {

                alert('Unable to generate UUID.');

            }
        }


        function copyUuid() {
            const input =
                document.getElementById('generatedUuid');

            navigator.clipboard.writeText(input.value);

            alert('UUID copied to clipboard.');
        }
    </script>

</body>

</html>