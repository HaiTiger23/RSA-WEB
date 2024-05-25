
# RSA Web






## Sinh khóa RSA
Quá trình sinh khóa RSA được diễn ra trong hàm sau:

Khi người dùng nhấn vào nút generate_button sẽ chạy hàm generateRsaKeys
```javascript
 generate_button.addEventListener("click", function() {
                generateRsaKeys().then(success, error);
    });

/**
 * Hàm generateRsaKeys tạo ra cặp khóa RSA mới.
 * Nó trả về một promise được resolve với một đối tượng chứa cặp khóa công khai và riêng tư.
 * @return {Promise} Promise trả về cặp khóa RSA
 */
var generateRsaKeys = function () {
    // Tạo ra khóa RSA mới
    return crypto.subtle.generateKey(rsaAlgorithm,
        /* extractable: */ true, /* keyUsages: */ ["wrapKey", "unwrapKey"])
        .catch(function (error) { throw "Error generating keys."; })
        .then(function (rsaKey) {
            // Xuất khóa công khai
            var exportPublicKey = crypto.subtle.exportKey("spki", rsaKey.publicKey)
                .catch(function (error) { throw "Error exporting public key."; });
            // Xuất khóa riêng tư
            var exportPrivateKey = crypto.subtle.exportKey("pkcs8", rsaKey.privateKey)
                .catch(function (error) { throw "Error exporting private key."; });

            // Trả về một promise được resolve với cặp khóa
            return Promise.all([exportPublicKey, exportPrivateKey])
                .then(function (keys) { return { publicKeyBuffer: keys[0], privateKeyBuffer: keys[1] }; });
        });
};
```

 **` Gọi crypto.subtle.generateKey`:**
   ```javascript
   return crypto.subtle.generateKey(rsaAlgorithm,
       /* extractable: */ true, /* keyUsages: */ ["wrapKey", "unwrapKey"])
       .catch(function (error) { throw "Error generating keys."; })
   ```
   - `crypto.subtle.generateKey` là hàm dùng để tạo cặp khóa (public key và private key).
   - `rsaAlgorithm` là đối tượng cấu hình, định nghĩa thuật toán và các tham số cần thiết cho việc tạo khóa.
   - `true` chỉ định rằng các khóa được tạo có thể xuất ra ngoài (extractable).
   - `["wrapKey", "unwrapKey"]` là mảng các mục đích sử dụng của khóa (key usages). Ở đây, khóa sẽ được dùng để bọc và mở bọc các khóa khác.
   - `.catch(function (error) { throw "Error generating keys."; })` để bắt lỗi nếu quá trình tạo khóa thất bại và ném ra thông báo lỗi.

 **Xử lý cặp khóa được tạo ra:**
   ```javascript
   .then(function (rsaKey) {
   ```
   Sau khi tạo khóa thành công, hàm `then` sẽ được gọi với `rsaKey` là đối tượng chứa cặp khóa (gồm public key và private key).

4. **Xuất khóa công khai:**
   ```javascript
   var exportPublicKey = crypto.subtle.exportKey("spki", rsaKey.publicKey)
       .catch(function (error) { throw "Error exporting public key."; });
   ```
   - `crypto.subtle.exportKey("spki", rsaKey.publicKey)` xuất khóa công khai dưới định dạng `spki`.
   - `.catch(function (error) { throw "Error exporting public key."; })` bắt lỗi nếu việc xuất khóa công khai thất bại.

5. **Xuất khóa riêng tư:**
   ```javascript
   var exportPrivateKey = crypto.subtle.exportKey("pkcs8", rsaKey.privateKey)
       .catch(function (error) { throw "Error exporting private key."; });
   ```
   - `crypto.subtle.exportKey("pkcs8", rsaKey.privateKey)` xuất khóa riêng tư dưới định dạng `pkcs8`.
   - `.catch(function (error) { throw "Error exporting private key."; })` bắt lỗi nếu việc xuất khóa riêng tư thất bại.

6. **Trả về Promise chứa cặp khóa:**
   ```javascript
   return Promise.all([exportPublicKey, exportPrivateKey])
       .then(function (keys) { return { publicKeyBuffer: keys[0], privateKeyBuffer: keys[1] }; });
   ```
   - `Promise.all([exportPublicKey, exportPrivateKey])` chờ cả hai promise (xuất khóa công khai và xuất khóa riêng tư) được hoàn thành.
   - `keys` là một mảng chứa hai giá trị trả về từ `exportPublicKey` và `exportPrivateKey`.
   - Hàm `then` tiếp theo lấy mảng `keys` và trả về một đối tượng có hai thuộc tính: `publicKeyBuffer` (chứa khóa công khai) và `privateKeyBuffer` (chứa khóa riêng tư).

Tóm lại, hàm `generateRsaKeys` tạo một cặp khóa RSA, xuất khóa công khai và khóa riêng tư, rồi trả về một promise chứa các buffer của hai khóa này. Đây là một cách tiếp cận không đồng bộ để tạo và xuất các khóa RSA trong môi trường JavaScript. 
     Sau hàm này chúng ta đã nhận được khóa công khai và khóa bí mật, Tiếp theo hiển thị ra giao diện cho người dùng.
     




## Mã hóa File 
### 1. Lấy Public Key từ file:
Đầu tiên, khi người dùng nhấn nút "Mã hóa ngay", đoạn mã sau sẽ được thực thi:
```javascript
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
```
**Giải thích:**
- Người dùng chọn file chứa public key.
- Nếu không chọn file, hiển thị lỗi "Bạn chưa chọn file public key!".
- Nếu có file, nội dung file sẽ được đọc và gọi hàm `encodeRsa(publicKey)` với nội dung của public key.

### 2. chạy hàm encodeRsa:
Hàm `encodeRsa(publicKey)` sẽ mã hóa file dựa trên public key đã đọc được:
```javascript
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
```
**Giải thích:**
- Kiểm tra public key, nếu trống hiển thị lỗi.
- Chuyển đổi public key từ định dạng PEM (chuỗi) sang ArrayBuffer.
- Kiểm tra nếu người dùng chưa chọn file để mã hóa, hiển thị lỗi.
- Đọc file cần mã hóa dưới dạng ArrayBuffer và gọi hàm `rsaEncrypt`.

### 3. Mã hóa file với RSA và AES:
Hàm `rsaEncrypt` thực hiện quá trình mã hóa bằng cách sử dụng kết hợp RSA và AES:
```javascript
var rsaEncrypt = function (data, rsaPublicKeyBuffer) {
    // Nhập khóa công khai RSA từ dữ liệu buffer
    var importRsaPublicKey = crypto.subtle.importKey("spki", rsaPublicKeyBuffer, rsaAlgorithm,
        /* extractable: */ false, /* keyUsages: */ ["wrapKey"])
        // Xử lý lỗi nếu có
        .catch(function (error) { console.log(error); throw "Error importing public key."; });

    // Tạo ra khóa AES mới
    var generateAesKey = crypto.subtle.generateKey(aesAlgorithm,
        /* extractable: */ true, /* keyUsages: */ ["encrypt"])
        // Xử lý lỗi nếu có
        .catch(function (error) { throw "Error generating symmetric key."; });

    // Chờ cả hai promise được resolve
    return Promise.all([importRsaPublicKey, generateAesKey])
        .then(function (keys) {
            var rsaPublicKey = keys[0], aesKey = keys[1];
            var aesIV = crypto.getRandomValues(new Uint8Array(aesIVLength)); // Tạo ra IV
            var initializedAesAlgorithm = Object.assign({ iv: aesIV }, aesAlgorithm); // Khởi tạo thuộc tính IV của thuật toán AES

            // Gộp các bước mã hóa và gói khóa lại vào một promise
            var wrapAesKey = crypto.subtle.wrapKey("raw", aesKey, rsaPublicKey, rsaAlgorithm)
                // Xử lý lỗi nếu có
                .catch(function (error) { throw "Error encrypting symmetric key."; });
            var encryptData = crypto.subtle.encrypt(initializedAesAlgorithm, aesKey, data)
                // Xử lý lỗi nếu có
                .catch(function (error) { throw "Error encrypting data."; });

            // Chờ cả hai promise được resolve và gộp dữ liệu đã được mã hóa
            return Promise.all([wrapAesKey, encryptData])
                .then(function (buffers) {
                    var wrappedAesKey = new Uint8Array(buffers[0]), encryptedData = new Uint8Array(buffers[1]);
                    var encryptionState = new Uint8Array(wrappedAesKey.length + aesIV.length + encryptedData.length);
                    encryptionState.set(wrappedAesKey, 0);
                    encryptionState.set(aesIV, wrappedAesKey.length);
                    encryptionState.set(encryptedData, wrappedAesKey.length + aesIV.length);
                    return encryptionState.buffer;
                });
        });
};
```
**Giải thích:**
- Import public key RSA từ định dạng ArrayBuffer.
- Tạo key AES ngẫu nhiên.
- Chờ cả hai quá trình trên hoàn thành.
- Sinh giá trị IV (Initialization Vector) ngẫu nhiên cho AES.
- Mã hóa key AES bằng public key RSA.
- Mã hóa dữ liệu bằng AES với IV đã sinh.
- Ghép nối key AES đã mã hóa, IV và dữ liệu đã mã hóa thành một mảng.
- Trả về mảng dữ liệu mã hóa hoàn chỉnh.

### Tổng kết:
Quá trình mã hóa file bằng RSA trong đoạn mã trên thực hiện các bước sau:
1. Đọc file chứa public key và chuyển đổi sang ArrayBuffer.
2. Đọc file cần mã hóa.
3. Tạo key AES và mã hóa dữ liệu bằng AES.
4. Mã hóa key AES bằng public key RSA.
5. Ghép nối key AES đã mã hóa, IV và dữ liệu đã mã hóa thành một mảng dữ liệu mã hóa hoàn chỉnh.

## Giải mã File

### 1. Sự kiện khi người dùng nhấn nút giải mã:
Khi người dùng nhấn vào nút giải mã, sự kiện sau được kích hoạt:
```javascript
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
```
**Giải thích:**
- Người dùng chọn file chứa private key.
- Nếu không chọn file, hiển thị lỗi "Bạn chưa chọn file private key!".
- Nếu có file, nội dung file sẽ được đọc và gọi hàm `process(privateKey)` với nội dung của private key.

### 2. Xử lý và giải mã file:
Hàm `process(privateKey)` xử lý quá trình giải mã file:
```javascript
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
```
**Giải thích:**
- Vô hiệu hóa nút giải mã để tránh nhấn nhiều lần.
- Kiểm tra private key, nếu trống hiển thị lỗi.
- Chuyển đổi private key từ định dạng PEM (chuỗi) sang ArrayBuffer.
- Kiểm tra nếu người dùng chưa chọn file để giải mã, hiển thị lỗi.
- Đọc file cần giải mã dưới dạng ArrayBuffer và gọi hàm `rsaDecrypt` để giải mã.

### 3. Giải mã file với RSA và AES:
Hàm `rsaDecrypt` thực hiện quá trình giải mã:
```javascript
var rsaDecrypt = function(data, rsaPrivateKeyBuffer) {
    // Import private key từ định dạng ArrayBuffer với thuật toán RSA
    return crypto.subtle.importKey("pkcs8", rsaPrivateKeyBuffer, rsaAlgorithm,
        /* extractable: */ false, /* keyUsages: */ ["unwrapKey"])
        // Nếu có lỗi khi import private key, bắt lỗi và ném thông báo lỗi
        .catch(function (error) { throw "Error importing private key."; })
        .then(function (rsaKey) {
            // Tính toán chiều dài của wrapped AES key
            var wrappedAesKeyLength = rsaAlgorithm.modulusLength / 8;
            // Trích xuất wrapped AES key từ phần đầu của dữ liệu đã mã hóa
            var wrappedAesKey = new Uint8Array(data.slice(0, wrappedAesKeyLength));
            // Trích xuất IV (Initialization Vector) từ dữ liệu đã mã hóa ngay sau wrapped AES key
            var aesIV = new Uint8Array(data.slice(wrappedAesKeyLength, wrappedAesKeyLength + aesIVLength));
            // Khởi tạo thuật toán AES với IV
            var initializedaesAlgorithm = Object.assign({ iv: aesIV }, aesAlgorithm);

            // Giải mã wrapped AES key bằng private key RSA để lấy lại AES key ban đầu
            return crypto.subtle.unwrapKey("raw", wrappedAesKey, rsaKey, rsaAlgorithm, initializedaesAlgorithm,
                /* extractable: */ false, /* keyUsages: */ ["decrypt"])
                // Nếu có lỗi khi giải mã AES key, bắt lỗi và ném thông báo lỗi
                .catch(function (error) { throw "Error decrypting symmetric key." })
                .then(function (aesKey) {
                    // Trích xuất dữ liệu đã mã hóa từ phần còn lại của dữ liệu
                    var encryptedData = new Uint8Array(data.slice(wrappedAesKeyLength + aesIVLength));

                    // Giải mã dữ liệu bằng AES key và IV
                    return crypto.subtle.decrypt(initializedaesAlgorithm, aesKey, encryptedData)
                        // Nếu có lỗi khi giải mã dữ liệu, bắt lỗi và ném thông báo lỗi
                        .catch(function (error) { throw "Error decrypting data." });
                });
        });
};

```
**Giải thích:**
- Import private key RSA từ định dạng ArrayBuffer.
- Tính toán chiều dài của wrapped AES key.
- Trích xuất wrapped AES key từ dữ liệu đã mã hóa.
- Trích xuất IV (Initialization Vector) từ dữ liệu đã mã hóa.
- Khởi tạo thuật toán AES với IV.
- Giải mã wrapped AES key bằng private key RSA để lấy lại AES key ban đầu.
- Giải mã dữ liệu bằng AES key và IV đã trích xuất.

### Tổng kết:
Quá trình giải mã file được mã hóa bằng khóa công khai RSA thực hiện các bước sau:
1. Đọc file chứa private key và chuyển đổi sang ArrayBuffer.
2. Đọc file cần giải mã dưới dạng ArrayBuffer.
3. Import private key RSA.
4. Trích xuất wrapped AES key và IV từ dữ liệu đã mã hóa.
5. Giải mã wrapped AES key bằng private key RSA để lấy lại AES key.
6. Giải mã dữ liệu bằng AES key và IV.