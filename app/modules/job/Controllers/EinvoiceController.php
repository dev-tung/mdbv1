<?php

namespace App\Job\Controllers;

class EinvoiceController
{
    /**
     * Viettel E-Invoice API
     */
    protected string $baseUrl =
        'https://api-vinvoice.viettel.vn/services/einvoiceapplication/api';


    /**
     * ========================================================
     * DOWNLOAD FILE HÓA ĐƠN
     *
     * 1. Login
     * 2. Lấy access_token
     * 3. Gọi API getInvoiceRepresentationFile
     * 4. In request / response
     * ========================================================
     */
    public function downloadFile()
    {
        echo '<pre>';

        /**
         * ====================================================
         * BƯỚC 1: LOGIN
         * ====================================================
         */

        $loginUrl = $this->baseUrl . '/auth/login';

        $loginData = [
            'username' => '0100109106-712',
            'password' => '12345678aA@',
        ];

        $loginJson = json_encode(
            $loginData,
            JSON_UNESCAPED_UNICODE
        );


        /**
         * ----------------------------------------------------
         * IN LOGIN REQUEST
         * ----------------------------------------------------
         */

        echo "================ LOGIN REQUEST ================\n\n";

        echo "METHOD:\n";
        echo "POST\n\n";

        echo "URL:\n";
        echo $loginUrl . "\n\n";

        echo "HEADERS:\n";

        print_r([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ]);

        echo "\nDATA:\n";

        print_r([
            'username' => $loginData['username'],
            'password' => '********',
        ]);

        echo "\nJSON:\n";

        echo json_encode(
            [
                'username' => $loginData['username'],
                'password' => '********',
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        echo "\n\n";


        /**
         * ----------------------------------------------------
         * CURL LOGIN
         * ----------------------------------------------------
         */

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $loginUrl,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 90,

            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],

            CURLOPT_POSTFIELDS => $loginJson,
        ]);

        $loginResponse = curl_exec($ch);

        $loginCurlError = curl_error($ch);

        $loginCurlErrno = curl_errno($ch);

        $loginHttpStatus = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        $loginContentType = curl_getinfo(
            $ch,
            CURLINFO_CONTENT_TYPE
        );

        curl_close($ch);


        /**
         * ====================================================
         * LOGIN RESPONSE
         * ====================================================
         */

        echo "================ LOGIN RESPONSE ================\n\n";

        echo "HTTP STATUS:\n";
        echo $loginHttpStatus . "\n\n";

        echo "CONTENT TYPE:\n";
        echo ($loginContentType ?: 'NONE') . "\n\n";

        echo "CURL ERRNO:\n";
        echo $loginCurlErrno . "\n\n";

        echo "CURL ERROR:\n";
        echo ($loginCurlError ?: 'NONE') . "\n\n";

        echo "RAW RESPONSE:\n";
        echo $loginResponse . "\n\n";


        /**
         * ====================================================
         * DECODE LOGIN RESPONSE
         * ====================================================
         */

        $loginResult = json_decode(
            $loginResponse,
            true
        );

        echo "================ DECODED LOGIN RESPONSE ================\n\n";

        if (json_last_error() !== JSON_ERROR_NONE) {

            echo "JSON ERROR:\n";
            echo json_last_error_msg();

            echo '</pre>';

            return;
        }

        print_r($loginResult);


        /**
         * ====================================================
         * LẤY ACCESS TOKEN
         * ====================================================
         */

        echo "\n\n================ ACCESS TOKEN ================\n\n";

        $accessToken = $loginResult['access_token'] ?? null;


        /**
         * Nếu login thất bại
         */
        if (!$accessToken) {

            echo "LOGIN THẤT BẠI\n\n";

            echo "Không tìm thấy access_token.\n\n";

            echo "HTTP STATUS:\n";
            echo $loginHttpStatus . "\n\n";

            echo "MESSAGE:\n";
            echo ($loginResult['message'] ?? 'UNKNOWN') . "\n\n";

            echo "DESCRIPTION:\n";
            echo ($loginResult['description'] ?? 'UNKNOWN') . "\n";

            echo '</pre>';

            return;
        }


        /**
         * Login thành công
         */
        echo "LOGIN THÀNH CÔNG\n\n";

        echo "ACCESS TOKEN:\n";
        echo $accessToken . "\n\n";


        /**
         * ====================================================
         * BƯỚC 2: GỌI API LẤY FILE HÓA ĐƠN
         * ====================================================
         */

        $fileUrl =
            $this->baseUrl .
            '/InvoiceAPI/InvoiceUtilsWS/getInvoiceRepresentationFile';


        /**
         * ----------------------------------------------------
         * DỮ LIỆU HÓA ĐƠN
         * ----------------------------------------------------
         */

        $fileData = [
            'supplierTaxCode' => '0100109106-712',
            'invoiceNo'       => 'AA/20E0000166',
            'templateCode'    => '01GTKT0/002',
            'fileType'        => 'ZIP',
        ];


        $fileJson = json_encode(
            $fileData,
            JSON_UNESCAPED_UNICODE
        );


        /**
         * ====================================================
         * DOWNLOAD REQUEST
         * ====================================================
         */

        echo "\n\n";
        echo "================ DOWNLOAD REQUEST ================\n\n";

        echo "METHOD:\n";
        echo "POST\n\n";

        echo "URL:\n";
        echo $fileUrl . "\n\n";

        echo "HEADERS:\n";

        print_r([
            'Cookie'       => 'access_token=' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ]);

        echo "\nDATA:\n";

        print_r($fileData);

        echo "\nJSON:\n";

        echo json_encode(
            $fileData,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        echo "\n\n";


        /**
         * ----------------------------------------------------
         * CURL DOWNLOAD
         * ----------------------------------------------------
         */

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $fileUrl,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 90,

            CURLOPT_HTTPHEADER => [
                'Cookie: access_token=' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ],

            CURLOPT_POSTFIELDS => $fileJson,
        ]);

        $fileResponse = curl_exec($ch);

        $fileCurlError = curl_error($ch);

        $fileCurlErrno = curl_errno($ch);

        $fileHttpStatus = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        $fileContentType = curl_getinfo(
            $ch,
            CURLINFO_CONTENT_TYPE
        );

        curl_close($ch);


        /**
         * ====================================================
         * DOWNLOAD RESPONSE
         * ====================================================
         */

        echo "================ DOWNLOAD RESPONSE ================\n\n";

        echo "HTTP STATUS:\n";
        echo $fileHttpStatus . "\n\n";

        echo "CONTENT TYPE:\n";
        echo ($fileContentType ?: 'NONE') . "\n\n";

        echo "CURL ERRNO:\n";
        echo $fileCurlErrno . "\n\n";

        echo "CURL ERROR:\n";
        echo ($fileCurlError ?: 'NONE') . "\n\n";

        echo "RAW RESPONSE:\n";
        echo $fileResponse . "\n\n";


        /**
         * ====================================================
         * DECODE RESPONSE
         * ====================================================
         */

        $fileResult = json_decode(
            $fileResponse,
            true
        );

        echo "================ DECODED RESPONSE ================\n\n";

        if (json_last_error() !== JSON_ERROR_NONE) {

            echo "JSON ERROR:\n";
            echo json_last_error_msg();

            echo '</pre>';

            return;
        }

        print_r($fileResult);


        /**
         * ====================================================
         * KẾT QUẢ HÓA ĐƠN
         * ====================================================
         */

        echo "\n\n";
        echo "================ INVOICE FILE ================\n\n";

        echo "errorCode:\n";

        print_r(
            $fileResult['errorCode'] ?? null
        );


        echo "\n\ndescription:\n";

        print_r(
            $fileResult['description'] ?? null
        );


        echo "\n\nfileName:\n";

        print_r(
            $fileResult['fileName'] ?? null
        );


        echo "\n\nfileToBytes:\n";


        /**
         * Có file
         */
        if (isset($fileResult['fileToBytes'])) {

            echo "CÓ fileToBytes\n\n";

            if (is_array($fileResult['fileToBytes'])) {

                echo "KIỂU DỮ LIỆU:\n";
                echo "ARRAY\n\n";

                echo "SỐ LƯỢNG BYTE:\n";
                echo count(
                    $fileResult['fileToBytes']
                );

            } else {

                echo "KIỂU DỮ LIỆU:\n";
                echo gettype(
                    $fileResult['fileToBytes']
                );
            }

        } else {

            echo "KHÔNG CÓ fileToBytes\n";
        }


        /**
         * ====================================================
         * KẾT THÚC
         * ====================================================
         */

        echo "\n\n";
        echo "================ END ================\n";

        echo '</pre>';
    }
}