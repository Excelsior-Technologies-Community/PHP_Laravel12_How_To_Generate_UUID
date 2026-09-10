<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>UUID Validator</title>

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
                UUID Validator
            </h1>

            <p class="text-muted">
                Validate a UUID and detect its version.
            </p>

        </div>

        <a
            href="{{ route('uuid.dashboard') }}"
            class="btn btn-dark">

            Dashboard

        </a>

    </div>


    <!-- Validator -->

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
                        value="{{ old('uuid', $result['uuid'] ?? '') }}"
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

                        <p class="mb-2">
                            {{ $result['message'] }}
                        </p>

                        <hr>

                        <div class="row">

                            <div class="col-md-6">

                                <strong>
                                    UUID:
                                </strong>

                                <br>

                                <code>
                                    {{ $result['uuid'] }}
                                </code>

                            </div>


                            <div class="col-md-6">

                                <strong>
                                    Detected Version:
                                </strong>

                                <br>

                                <span class="badge bg-primary">

                                    {{ $result['version'] }}

                                </span>

                            </div>

                        </div>


                        <div class="mt-3">

                            @if($result['exists_in_history'])

                                <span class="badge bg-success">

                                    ✓ Found in generation history

                                </span>

                                @if($result['generated_type'])

                                    <span class="ms-2">

                                        Generated as:
                                        <strong>
                                            {{ $result['generated_type'] }}
                                        </strong>

                                    </span>

                                @endif

                            @else

                                <span class="badge bg-secondary">

                                    Not found in local generation history

                                </span>

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


    <!-- UUID Information -->

    <div class="card shadow-sm border-0 mt-4">

        <div class="card-body">

            <h5 class="fw-bold">
                UUID Version Detection
            </h5>

            <p class="text-muted mb-0">

                The validator checks the UUID structure and reads
                the UUID version field to identify versions such as
                UUID v4 and UUID v7.

            </p>

        </div>

    </div>

</div>

</body>

</html>