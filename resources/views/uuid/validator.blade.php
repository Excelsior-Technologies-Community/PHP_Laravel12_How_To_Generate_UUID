<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UUID Validator</title>

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
                UUID Validator
            </h1>

            <p class="text-muted">
                Validate UUID format, detect version and inspect history.
            </p>

        </div>


        <a
            href="{{ route('uuid.dashboard') }}"
            class="btn btn-dark">

            Dashboard

        </a>

    </div>


    <!-- VALIDATOR -->

    <div class="card shadow-sm border-0">


        <div class="card-header bg-white">

            <h4 class="mb-0">
                Validate UUID
            </h4>

        </div>


        <div class="card-body">


            <form
                method="POST"
                action="{{ route('uuid.validator.check') }}">


                @csrf


                <label class="form-label fw-semibold">

                    Enter UUID

                </label>


                <div class="input-group">


                    <input
                        type="text"
                        name="uuid"
                        class="form-control"
                        value="{{ old(
                            'uuid',
                            $result['uuid'] ?? ''
                        ) }}"
                        placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                        required>


                    <button
                        class="btn btn-primary"
                        type="submit">

                        Validate UUID

                    </button>


                </div>


                @error('uuid')

                    <div class="text-danger mt-2">

                        {{ $message }}

                    </div>

                @enderror


            </form>


            @isset($result)


                <hr class="my-4">


                @if($result['valid'])


                    <div class="alert alert-success">


                        <h5 class="fw-bold">

                            ✓ Valid UUID

                        </h5>


                        <p>
                            {{ $result['message'] }}
                        </p>


                        <hr>


                        <div class="row g-4">


                            <!-- UUID -->

                            <div class="col-md-6">

                                <strong>
                                    UUID:
                                </strong>

                                <br>

                                <code>
                                    {{ $result['uuid'] }}
                                </code>

                            </div>


                            <!-- VERSION -->

                            <div class="col-md-6">

                                <strong>
                                    Detected Version:
                                </strong>

                                <br>

                                <span class="badge bg-primary">

                                    {{ $result['version'] }}

                                </span>

                            </div>


                            <!-- HISTORY -->

                            <div class="col-md-6">

                                <strong>
                                    Generation History:
                                </strong>

                                <br>


                                @if($result['exists_in_history'])

                                    <span class="badge bg-success">

                                        Found in history

                                    </span>


                                    @if($result['generated_type'])

                                        <div class="mt-2">

                                            Generated as:

                                            <strong>

                                                {{ $result['generated_type'] }}

                                            </strong>

                                        </div>

                                    @endif

                                @else

                                    <span class="badge bg-secondary">

                                        Not found in history

                                    </span>

                                @endif

                            </div>


                            <!-- UNIQUENESS -->

                            <div class="col-md-6">

                                <strong>
                                    Uniqueness:
                                </strong>

                                <br>


                                @if($result['is_unique'])

                                    <span class="badge bg-success">

                                        ✓ Not found in local history

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        Already generated

                                    </span>

                                @endif

                            </div>


                            <!-- TIMESTAMP -->

                            @if($result['timestamp'])

                                <div class="col-md-12">

                                    <div class="alert alert-info mb-0">

                                        <strong>
                                            UUID v7 Timestamp
                                        </strong>

                                        <br>

                                        This UUID contains a timestamp
                                        corresponding approximately to:

                                        <strong>
                                            {{ $result['timestamp'] }}
                                        </strong>

                                    </div>

                                </div>

                            @endif


                        </div>


                    </div>


                @else


                    <div class="alert alert-danger">


                        <h5 class="fw-bold">

                            ✗ Invalid UUID

                        </h5>


                        <p class="mb-0">

                            {{ $result['message'] }}

                        </p>


                    </div>


                @endif


            @endisset


        </div>

    </div>


    <!-- INFORMATION -->

    <div class="card shadow-sm border-0 mt-4">


        <div class="card-body">


            <h5 class="fw-bold">

                UUID Version Detection

            </h5>


            <p class="text-muted mb-0">

                The validator checks the UUID structure,
                detects its version and checks whether
                the UUID exists in the local generation history.

            </p>


        </div>

    </div>


</div>


</body>

</html>