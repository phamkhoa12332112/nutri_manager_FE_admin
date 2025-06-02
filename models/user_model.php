<?php

class UserModel {
    public function getAllUsers() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/users/all");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            return null;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return $data['data'] ?? null; // giả sử response có dạng { msg, stateCode, data }
    }

    public function getDetailsById($id) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/users/details/" . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            return null;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return $data['data'] ?? null; // giả sử response có dạng { msg, stateCode, data }
    }
}
