<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mã hóa file Docx, PDF') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <div class="mb-2 flex justify-between">
                            <x-input-label for="name" :value="__('File Public Key')" />
                        </div>
                        <input
                            class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                            id="key_file" type="file">

                    </div>
                    <div class="mb-4">
                        <div class="mb-2 flex justify-between">
                            <x-input-label for="name" :value="__('File mã hóa')" />
                        </div>
                        <input
                            class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                            id="file-to-encrypt" accept="application/pdf, application/msword" type="file">

                    </div>

                    <div class="flex justify-center">
                        <x-primary-button id="encode_button">{{ __('Mã hóa ngay') }}</x-primary-button>
                    </div>

                    <div id="result" class="mt-2 hidden">
                        <div class="flex flex-col items-center">
                            <x-input-label for="name" :value="__('File đã mã hóa')" />
                            <a id="encrypted-file"><x-secondary-button>Tải xuống ngay</x-secondary-button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script>
            var fileToEncrypt = document.getElementById("file-to-encrypt");
            var encryptedFile = document.getElementById("encrypted-file");
            var result = document.getElementById("result");
            document.getElementById('encode_button').addEventListener('click', () => {
                var publicKeyFile = document.getElementById('key_file').files[0];
                var fileReader = new FileReader();
                if (!publicKeyFile) {
                    error('Bạn chưa chọn file public key!');
                    return;
                }

                fileReader.onload = function() {
                    var publicKey = fileReader.result;

                    encodeRsa(publicKey);
                };
                fileReader.readAsText(publicKeyFile);
            });

            /**
             * Mã hóa file sử dụng khóa công khai
             *
             * @param {string} publicKey - Khóa công khai được đọc từ file
             * @return {void}
             */
            function encodeRsa(publicKey) {
                // Xóa khoảng trắng ở đầu và cuối của khóa
                if (publicKey.trim() === "")
                    return error("Không tìm thấy public key.");

                var publicKeyArrayBuffer = null;
                try {
                    // Chuyển đổi khóa công khai từ PEM sang ArrayBuffer
                    publicKeyArrayBuffer = pemToArrayBuffer(publicKey.trim());
                } catch (e) {
                    return error("Public key không hợp lệ.");
                }

                // Kiểm tra xem có file cần mã hóa hay không
                if (!fileToEncrypt.files.length)
                    return error("Bạn chưa chọn file cần mã hóa.");

                var fileReader = new FileReader();
                fileReader.onload = function() {
                    // Mã hóa file sử dụng khóa công khai
                    rsaEncrypt(this.result, publicKeyArrayBuffer).then(success, error);
                };
                fileReader.readAsArrayBuffer(fileToEncrypt.files[0]);
            }

            function error(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error,
                })
                return;
            }

            function message_success(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Done...',
                    text: message,
                })
            }

            var success = function(data) {
                var unencryptedFile = fileToEncrypt.files[0];

                encryptedFile.href = window.URL.createObjectURL(
                    new Blob([data], {
                        type: "application/octet-stream"
                    }));
                encryptedFile.download = encryptedFile.innerText = unencryptedFile.name + ".encrypted";
                result.style.display = "block";
                message_success("Đã mã hóa file thành công, hãy tải nó")
                button.disabled = false;
            };
        </script>
    </x-slot>
</x-app-layout>
