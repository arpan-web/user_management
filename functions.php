<?php
// functions.php - utilitas & kirim email (PHPMailer)
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $toName, $subject, $bodyHtml) {
    $mail = new PHPMailer(true);
    try {
        // === Konfigurasi dasar SMTP Gmail ===
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Arfantri78@gmail.com';        // Email admin (pengirim)
        $mail->Password = 'csem aief wgfn qqym';         // App Password Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // === Pengaturan pengirim dan penerima ===
        $mail->setFrom('Arfantri78@gmail.com', 'Admin Gudang');
        $mail->addAddress($to, $toName);                 // Kirim ke user baru

        // === Tambahkan agar admin juga dapat salinan ===
        $mail->addBCC('Arfantri78@gmail.com', 'Admin Gudang'); 

        // === Konten email ===
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        $mail->send();

        // === Kirim email notifikasi terpisah ke admin ===
        // Bisa diaktifkan jika ingin admin dapat email berbeda dari user
        $adminMail = new PHPMailer(true);
        $adminMail->isSMTP();
        $adminMail->Host = 'smtp.gmail.com';
        $adminMail->SMTPAuth = true;
        $adminMail->Username = 'Arfantri78@gmail.com';
        $adminMail->Password = 'phntvrygjphmybt';
        $adminMail->SMTPSecure = 'tls';
        $adminMail->Port = 587;

        $adminMail->setFrom('Arfantri78@gmail.com', 'Sistem Gudang');
        $adminMail->addAddress('Arfantri78@gmail.com', 'Admin Gudang');
        $adminMail->isHTML(true);
        $adminMail->Subject = 'Notifikasi Registrasi User Baru';
        $adminMail->Body = "
            <p><b>Halo Admin,</b></p>
            <p>Seorang user baru telah melakukan registrasi:</p>
            <ul>
                <li>Email: <b>$to</b></li>
                <li>Nama Lengkap: <b>$toName</b></li>
                <li>Waktu: " . date('d-m-Y H:i:s') . "</li>
            </ul>
            <p>Silakan cek dashboard admin untuk melihat data user lebih lanjut.</p>
        ";
        $adminMail->send();

        return true;
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }
}

// === Fungsi untuk cek login ===
function isLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}
