<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RSA WEB</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.4/dist/flowbite.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">

    <body class="antialiased">
        <div
            class="bg-dots-darker dark:bg-dots-lighter relative min-h-screen bg-gray-100 bg-center selection:bg-red-500 selection:text-white dark:bg-gray-900 sm:flex sm:items-center sm:justify-center">
            @if (Route::has('login'))
                <div class="z-10 p-6 text-right sm:fixed sm:right-0 sm:top-0">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-gray-400 dark:hover:text-white">Sinh mã RSA</a>
                              <a href="{{ route('encode-rsa.index') }}"
                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-gray-400 dark:hover:text-white">Mã hóa file</a>
                    @else

                      <div class="flex justify-center">
                          <a href="{{ route('login') }}"
                            class="font-semibold text-center text-gray-600 hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-gray-400 dark:hover:text-white">Đăng nhập ngay để có thể <br> sinh mã và mã hóa file</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-gray-400 dark:hover:text-white">Đăng
                                ký</a>
                        @endif
                      </div>
                    @endauth
                </div>
            @endif

            <div class="mx-auto w-full p-6 lg:p-8">
                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="200" zoomAndPan="magnify" viewBox="0 0 150 149.999998" height="200" preserveAspectRatio="xMidYMid meet" version="1.0"><defs><g/></defs><g fill="#ff3131" fill-opacity="1"><g transform="translate(0.00000125, 94.751739)"><g><path d="M 19.03125 -31.328125 L 22.890625 -31.328125 C 26.660156 -31.328125 29.441406 -31.953125 31.234375 -33.203125 C 33.035156 -34.460938 33.9375 -36.441406 33.9375 -39.140625 C 33.9375 -41.816406 33.019531 -43.71875 31.1875 -44.84375 C 29.351562 -45.976562 26.507812 -46.546875 22.65625 -46.546875 L 19.03125 -46.546875 Z M 19.03125 -21.609375 L 19.03125 0 L 7.09375 0 L 7.09375 -56.328125 L 23.5 -56.328125 C 31.15625 -56.328125 36.816406 -54.929688 40.484375 -52.140625 C 44.160156 -49.359375 46 -45.128906 46 -39.453125 C 46 -36.140625 45.085938 -33.191406 43.265625 -30.609375 C 41.441406 -28.023438 38.859375 -26.003906 35.515625 -24.546875 L 52.09375 0 L 38.84375 0 L 25.390625 -21.609375 Z M 19.03125 -21.609375 "/></g></g></g><g fill="#ff3131" fill-opacity="1"><g transform="translate(52.090057, 94.751739)"><g><path d="M 40.34375 -15.640625 C 40.34375 -10.554688 38.507812 -6.550781 34.84375 -3.625 C 31.1875 -0.695312 26.097656 0.765625 19.578125 0.765625 C 13.566406 0.765625 8.25 -0.363281 3.625 -2.625 L 3.625 -13.71875 C 7.425781 -12.019531 10.640625 -10.820312 13.265625 -10.125 C 15.898438 -9.4375 18.3125 -9.09375 20.5 -9.09375 C 23.113281 -9.09375 25.117188 -9.59375 26.515625 -10.59375 C 27.921875 -11.59375 28.625 -13.082031 28.625 -15.0625 C 28.625 -16.164062 28.316406 -17.144531 27.703125 -18 C 27.085938 -18.863281 26.179688 -19.691406 24.984375 -20.484375 C 23.785156 -21.285156 21.351562 -22.5625 17.6875 -24.3125 C 14.238281 -25.925781 11.65625 -27.476562 9.9375 -28.96875 C 8.21875 -30.457031 6.84375 -32.191406 5.8125 -34.171875 C 4.789062 -36.148438 4.28125 -38.460938 4.28125 -41.109375 C 4.28125 -46.085938 5.96875 -50.003906 9.34375 -52.859375 C 12.71875 -55.710938 17.382812 -57.140625 23.34375 -57.140625 C 26.269531 -57.140625 29.0625 -56.789062 31.71875 -56.09375 C 34.382812 -55.40625 37.164062 -54.429688 40.0625 -53.171875 L 36.21875 -43.890625 C 33.207031 -45.117188 30.71875 -45.976562 28.75 -46.46875 C 26.789062 -46.957031 24.863281 -47.203125 22.96875 -47.203125 C 20.707031 -47.203125 18.972656 -46.671875 17.765625 -45.609375 C 16.554688 -44.554688 15.953125 -43.1875 15.953125 -41.5 C 15.953125 -40.445312 16.195312 -39.523438 16.6875 -38.734375 C 17.175781 -37.953125 17.953125 -37.195312 19.015625 -36.46875 C 20.078125 -35.738281 22.597656 -34.421875 26.578125 -32.515625 C 31.847656 -30.003906 35.457031 -27.484375 37.40625 -24.953125 C 39.363281 -22.421875 40.34375 -19.316406 40.34375 -15.640625 Z M 40.34375 -15.640625 "/></g></g></g><g fill="#ff3131" fill-opacity="1"><g transform="translate(95.549808, 94.751739)"><g><path d="M 41.578125 0 L 37.484375 -13.40625 L 16.953125 -13.40625 L 12.875 0 L 0 0 L 19.875 -56.5625 L 34.484375 -56.5625 L 54.4375 0 Z M 34.640625 -23.421875 C 30.859375 -35.578125 28.726562 -42.445312 28.25 -44.03125 C 27.78125 -45.625 27.441406 -46.882812 27.234375 -47.8125 C 26.390625 -44.519531 23.960938 -36.390625 19.953125 -23.421875 Z M 34.640625 -23.421875 "/></g></g></g><g fill="#ff3131" fill-opacity="1"><g transform="translate(117.468746, 117.544368)"><g><path d="M 2.984375 0 L 0.234375 -13.390625 L 3.109375 -13.5 L 4.53125 -5.421875 L 6.0625 -13.390625 L 8.21875 -13.5 L 9.640625 -5.328125 L 11.359375 -13.390625 L 13.78125 -13.5 L 10.890625 0 L 8.28125 0 L 6.859375 -8.25 L 5.40625 0 Z M 2.984375 0 "/></g></g></g><g fill="#ff3131" fill-opacity="1"><g transform="translate(131.391707, 117.544368)"><g><path d="M 1.265625 0 L 1.265625 -13.5 L 8.125 -13.5 L 7.90625 -11.390625 L 3.90625 -11.390625 L 3.90625 -8.171875 L 6.75 -8.171875 L 6.734375 -5.90625 L 3.90625 -5.90625 L 3.90625 -2.109375 L 8.265625 -2.109375 L 8.046875 0 Z M 1.265625 0 "/></g></g></g><g fill="#ff3131" fill-opacity="1"><g transform="translate(140.080073, 117.544368)"><g><path d="M 1.265625 -13.5 L 4.28125 -13.5 C 5.914062 -13.5 7.101562 -13.179688 7.84375 -12.546875 C 8.59375 -11.910156 8.96875 -11.070312 8.96875 -10.03125 C 8.96875 -9.363281 8.835938 -8.78125 8.578125 -8.28125 C 8.316406 -7.78125 7.957031 -7.414062 7.5 -7.1875 C 8.082031 -6.882812 8.539062 -6.484375 8.875 -5.984375 C 9.207031 -5.484375 9.375 -4.75 9.375 -3.78125 C 9.375 -1.257812 7.390625 0 3.421875 0 L 1.265625 0 Z M 4.453125 -8.015625 C 5.140625 -8.015625 5.65625 -8.125 6 -8.34375 C 6.34375 -8.570312 6.515625 -9.039062 6.515625 -9.75 C 6.515625 -10.375 6.316406 -10.800781 5.921875 -11.03125 C 5.535156 -11.269531 5.007812 -11.390625 4.34375 -11.390625 L 3.8125 -11.390625 L 3.8125 -8.015625 Z M 4.390625 -2.109375 C 5.253906 -2.109375 5.878906 -2.320312 6.265625 -2.75 C 6.648438 -3.175781 6.84375 -3.640625 6.84375 -4.140625 C 6.84375 -4.921875 6.632812 -5.4375 6.21875 -5.6875 C 5.800781 -5.945312 5.222656 -6.078125 4.484375 -6.078125 L 3.90625 -6.078125 L 3.90625 -2.109375 Z M 4.390625 -2.109375 "/></g></g></g></svg>
                </div>
                <div class="">
                    <div class="">
                        <div
                            class="duration-250 flex scale-100 justify-center rounded-lg bg-white from-gray-700/50 via-transparent p-6 shadow-2xl shadow-gray-500/20 transition-all focus:outline focus:outline-2 focus:outline-red-500 dark:bg-gray-800/50 dark:bg-gradient-to-bl dark:shadow-none dark:ring-1 dark:ring-inset dark:ring-white/5">
                            <div>
                                <h2 class="mt-6 text-center text-[42px] font-semibold text-gray-900 dark:text-white">
                                    Giải mã file RSA</h2>
                                <p class="mb-6 text-white">Hãy tải lên file cần giải mã và file private
                                    key của bạn để giải mã file mã hóa bằng RSA </p>
                                <div class="mb-6 flex w-full items-center justify-center">
                                    <label for="encrypted-file"
                                        class="dark:hover:bg-bray-800 flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pb-6 pt-5">
                                            <svg class="mb-4 h-8 w-8 text-gray-500 dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p id="encrypted-file-label"
                                                class="text-md mb-2 text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Nhấn vào đây để tải lên file cần giải mã</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                            </p>
                                        </div>
                                        <input id="encrypted-file" accept=".encrypted" type="file" class="hidden" />
                                    </label>
                                </div>
                                <div class="s mb-6">
                                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        for="key_file">File Private Key</label>
                                    <input
                                        class="mb-5 block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                                        id="key_file" type="file" accept=".rsa">
                                </div>
                                <div class="flex justify-center">
                                    <button id="button"
                                    class="mb-6 w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-auto">Giải
                                    mã ngay</button>
                                </div>
                                <div class="hidden justify-center" id="result">
                                    <a type="button" id="decrypted-file"
                                        class="mb-2 me-2 flex items-center space-x-2 rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-900">
                                        <svg class="h-6 w-6 text-gray-800 dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3">
                                            </path>
                                        </svg>
                                        <span> Tải xuống file đã giải mã</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/encoding-helper.js') }}"></script>
        <script src="{{ asset('js/encryption-helper.js') }}"></script>
        <script>
            (function() {

                var encryptedFile = document.getElementById("encrypted-file");
                var button = document.getElementById("button");
                var message = document.getElementById("message");
                var decryptedFile = document.getElementById("decrypted-file");
                var result = document.getElementById("result");
                var encryptedFileLabel = document.getElementById("encrypted-file-label");

                var success = function(data) {
                    var encryptedFile2 = encryptedFile.files[0];

                    decryptedFile.href = window.URL.createObjectURL(
                        new Blob([data], {
                            type: "application/octet-stream"
                        }));
                    decryptedFile.download = decryptedFile.innerText = encryptedFile2.name.replace(".encrypted", "");
                    result.style.display = "flex";

                    message_success("Giải mã thành công");
                    button.disabled = false;
                };

                function error(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error,
                    })
                     button.disabled = false;
                    return;
                }

                function message_success(message) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Done...',
                        text: message,
                    })

                    encryptedFile.value = "";
                    document.getElementById('key_file').value = "";
                    encryptedFileLabel.innerText = "Nhấn vào đây để tải lên file cần giải mã";
                }

                var process = function(privateKey) {

                    button.disabled = true;

                    if (privateKey.trim() === "")
                        return error("Không tìm thấy private key.");

                    var privateKeyArrayBuffer = null;
                    try {
                        privateKeyArrayBuffer = pemToArrayBuffer(privateKey.trim());
                    } catch (_) {
                        return error("Private key không hợp lệ.");
                    }

                    if (!encryptedFile.files.length)
                        return error("Bạn chưa chọn file cần giải mã.");

                    var fileReader = new FileReader();
                    fileReader.onload = function() {
                        rsaDecrypt(this.result, privateKeyArrayBuffer).then(success, error);
                    };
                    fileReader.readAsArrayBuffer(encryptedFile.files[0]);
                };

                button.addEventListener("click", () => {
                    var privateKey = document.getElementById('key_file').files[0];
                    var fileReader = new FileReader();
                    if (!privateKey) {
                        error('Bạn chưa chọn file private key!');
                        return;
                    }

                    fileReader.onload = function() {
                        var privateKey = fileReader.result;
                        process(privateKey);
                    };
                    fileReader.readAsText(privateKey);
                });
                encryptedFile.addEventListener("change", () => {
                    var file = encryptedFile.files[0];
                    if (file)
                        encryptedFileLabel.innerText = file.name;
                    else
                        encryptedFileLabel.innerText = "File to decrypt";
                });
            })();
        </script>

        </div>
    </body>

</html>
