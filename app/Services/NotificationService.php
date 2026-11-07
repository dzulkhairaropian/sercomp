<?php

namespace App\Services;

use App\Mail\ServiceOrderMail;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Format phone number to Indonesian international format (628xxx) for WhatsApp wa.me.
     */
    public static function formatWaPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Generate direct WhatsApp wa.me click-to-chat URL.
     */
    public static function buildWaLink(string $phone, string $message): string
    {
        $waPhone = self::formatWaPhone($phone);
        return 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($message);
    }

    /**
     * Dispatch email notification safely.
     */
    protected static function dispatchEmail(ServiceOrder $order, string $subject, string $headline, string $message): void
    {
        $email = $order->customer?->email;
        if (!empty($email) && !str_contains($email, '@guest.sercomp.id')) {
            try {
                Mail::to($email)->send(new ServiceOrderMail($order, $subject, $headline, $message));
                Log::info("[NotificationService] Email sent to {$email} for ticket {$order->ticket_no}");
            } catch (\Throwable $e) {
                Log::warning("[NotificationService] Failed to send email to {$email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Log WhatsApp dispatch.
     */
    protected static function logWaDispatch(ServiceOrder $order, string $message): void
    {
        $phone = $order->customer?->phone;
        Log::info("[NotificationService][WhatsApp] Dispatched to {$phone} ({$order->ticket_no}):\n{$message}");
    }

    /**
     * 1. Notif saat booking baru dibuat.
     */
    public static function notifyBookingCreated(ServiceOrder $order): array
    {
        $customerName = $order->customer?->name ?? 'Pelanggan';
        $trackingUrl = url('/user-panel?ticket=' . $order->ticket_no);

        $waMessage = "Halo {$customerName}, reservasi servis komputer Anda di SerComp berhasil terdaftar!\n\n"
            . "📌 No. Tiket: *{$order->ticket_no}*\n"
            . "🔐 PIN Keamanan: *{$order->security_pin}*\n"
            . "💻 Unit: {$order->device_type} {$order->device_brand} {$order->device_model}\n"
            . "📦 Metode: {$order->delivery_method}\n"
            . "Status saat ini: *Booking*\n\n"
            . "Silakan pantau status pengerjaan secara real-time di:\n{$trackingUrl}\n\n"
            . "Terima kasih telah mempercayakan perbaikan di SerComp.";

        self::dispatchEmail(
            $order,
            "Reservasi Servis Berhasil Dibuat",
            "Reservasi Servis Telah Diterbitkan",
            "Unit Anda telah terdaftar dalam sistem workshop SerComp. Teknisi kami akan memeriksa hardware dan merilis lembar estimasi biaya resmi."
        );

        self::logWaDispatch($order, $waMessage);

        return [
            'wa_message' => $waMessage,
            'wa_link' => self::buildWaLink($order->customer?->phone ?? '', $waMessage),
        ];
    }

    /**
     * 2. Notif saat admin merilis estimasi biaya jasa & suku cadang.
     */
    public static function notifyEstimateReady(ServiceOrder $order): array
    {
        $customerName = $order->customer?->name ?? 'Pelanggan';
        $trackingUrl = url('/user-panel?ticket=' . $order->ticket_no);
        $total = number_format($order->diagnostic?->net_total_cost ?? 0, 0, ',', '.');
        $labor = number_format($order->diagnostic?->labor_cost ?? 0, 0, ',', '.');
        $parts = number_format($order->diagnostic?->parts_cost ?? 0, 0, ',', '.');

        $waMessage = "Halo {$customerName}, estimasi biaya servis untuk tiket *{$order->ticket_no}* telah diterbitkan oleh Admin SerComp!\n\n"
            . "💻 Unit: {$order->device_type} {$order->device_brand} {$order->device_model}\n"
            . "🔧 Analisis: " . ($order->diagnostic?->physical_damage_analysis ?? '-') . "\n"
            . "📋 Biaya Jasa: Rp {$labor}\n"
            . "⚙️ Suku Cadang: Rp {$parts}\n"
            . "💰 *Total Biaya Terkunci: Rp {$total}*\n\n"
            . "Silakan tinjau rincian, setujui estimasi, atau ajukan negosiasi di link berikut:\n{$trackingUrl}\n\n"
            . "Pengerjaan baru akan dimulai setelah ada persetujuan dari Anda (Zero Hidden Cost).";

        self::dispatchEmail(
            $order,
            "Estimasi Biaya Jasa & Sparepart Telah Rilis",
            "Estimasi Biaya Servis Telah Ditetapkan Admin",
            "Admin SerComp telah meninjau kerusakan fisik unit Anda dan menerbitkan rincian biaya resmi terkunci sebesar Rp {$total}. Silakan tinjau dan setujui untuk lanjut pembayaran QRIS."
        );

        self::logWaDispatch($order, $waMessage);

        return [
            'wa_message' => $waMessage,
            'wa_link' => self::buildWaLink($order->customer?->phone ?? '', $waMessage),
        ];
    }

    /**
     * 3. Notif saat customer mengajukan nego biaya.
     */
    public static function notifyNegotiationRequested(ServiceOrder $order): array
    {
        $customerName = $order->customer?->name ?? 'Pelanggan';
        $adminPhone = config('services.sercomp.admin_wa', '081122334455');
        $budget = number_format($order->negotiation_budget ?? 0, 0, ',', '.');

        $waMessage = "Halo Admin SerComp, pelanggan *{$customerName}* mengajukan negosiasi biaya pada tiket *{$order->ticket_no}*!\n\n"
            . "💻 Unit: {$order->device_type} {$order->device_brand}\n"
            . "💰 Total Estimasi Saat Ini: Rp " . number_format($order->diagnostic?->net_total_cost ?? 0, 0, ',', '.') . "\n"
            . "🏷️ Tawaran/Budget Pelanggan: Rp {$budget}\n"
            . "📝 Catatan Nego: \"{$order->negotiation_notes}\"\n\n"
            . "Silakan tinjau di panel admin: " . url('/teknisi/meja-kerja/' . $order->ticket_no);

        self::dispatchEmail(
            $order,
            "Pengajuan Negosiasi Biaya Diterima",
            "Permintaan Negosiasi Sedang Ditinjau Admin",
            "Kami telah menerima pengajuan nego biaya servis Anda sebesar Rp {$budget}. Admin workshop sedang meninjau opsi penyesuaian suku cadang atau diskon jasa terbaik untuk Anda."
        );

        self::logWaDispatch($order, $waMessage);

        return [
            'wa_message' => $waMessage,
            'wa_link' => self::buildWaLink($adminPhone, $waMessage),
        ];
    }

    /**
     * 4. Notif saat admin menanggapi pengajuan nego (disetujui / ditolak).
     */
    public static function notifyNegotiationResolved(ServiceOrder $order, bool $approved): array
    {
        $customerName = $order->customer?->name ?? 'Pelanggan';
        $trackingUrl = url('/user-panel?ticket=' . $order->ticket_no);
        $total = number_format($order->diagnostic?->net_total_cost ?? 0, 0, ',', '.');

        if ($approved) {
            $waMessage = "Halo {$customerName}, kabar baik! Negosiasi biaya untuk tiket *{$order->ticket_no}* telah DISETUJUI oleh Admin SerComp!\n\n"
                . "💰 Total Biaya Baru: *Rp {$total}*\n"
                . "Catatan Admin: " . ($order->diagnostic?->admin_notes ?? 'Harga telah disesuaikan.') . "\n\n"
                . "Silakan buka link berikut untuk menyetujui dan melakukan pembayaran QRIS:\n{$trackingUrl}";

            $subject = "Negosiasi Biaya Servis Disetujui!";
            $headline = "Kesepakatan Biaya Baru Telah Disetujui";
            $detail = "Admin SerComp telah menyetujui pengajuan negosiasi Anda. Total biaya kini disesuaikan menjadi Rp {$total}.";
        } else {
            $waMessage = "Halo {$customerName}, terkait pengajuan nego tiket *{$order->ticket_no}*, mohon maaf harga belum dapat disesuaikan.\n\n"
                . "Catatan Admin: " . ($order->diagnostic?->admin_notes ?? 'Suku cadang original dengan standar garansi 90 hari.') . "\n"
                . "💰 Biaya Tetap: *Rp {$total}*\n\n"
                . "Anda dapat melanjutkan persetujuan biaya atau membatalkan servis tanpa biaya di link:\n{$trackingUrl}";

            $subject = "Pemberitahuan Negosiasi Biaya Servis";
            $headline = "Tanggapan Admin Mengenai Negosiasi Biaya";
            $detail = "Admin SerComp telah meninjau pengajuan nego Anda. " . ($order->diagnostic?->admin_notes ?? 'Harga saat ini merupakan standar kualitas suku cadang original bergaransi.');
        }

        self::dispatchEmail($order, $subject, $headline, $detail);
        self::logWaDispatch($order, $waMessage);

        return [
            'wa_message' => $waMessage,
            'wa_link' => self::buildWaLink($order->customer?->phone ?? '', $waMessage),
        ];
    }

    /**
     * 5. Notif saat reparasi selesai.
     */
    public static function notifyOrderCompleted(ServiceOrder $order): array
    {
        $customerName = $order->customer?->name ?? 'Pelanggan';
        $trackingUrl = url('/user-panel?ticket=' . $order->ticket_no);

        $waMessage = "Halo {$customerName}, perbaikan unit *{$order->device_type} {$order->device_brand}* (Tiket *{$order->ticket_no}*) telah SELESAI!\n\n"
            . "✅ Unit lolos running test performa normal & suhu stabil.\n"
            . "✅ Suku cadang lama telah diamankan untuk dikembalikan kepada Anda.\n\n"
            . "Unit siap diantar oleh kurir / diambil di workshop. Cek foto bukti purnajual di:\n{$trackingUrl}";

        self::dispatchEmail(
            $order,
            "Servis Unit Telah Selesai Dikerjakan",
            "Perbaikan Unit Selesai & Lolos Uji Performa",
            "Teknisi telah menyelesaikan perbaikan unit Anda. Unit telah lulus running test dan siap untuk diserahterimakan."
        );

        self::logWaDispatch($order, $waMessage);

        return [
            'wa_message' => $waMessage,
            'wa_link' => self::buildWaLink($order->customer?->phone ?? '', $waMessage),
        ];
    }

    /**
     * 6. Notif serah terima unit oleh kurir & tanda tangan user.
     */
    public static function notifyOrderHandedOver(ServiceOrder $order): array
    {
        $customerName = $order->customer?->name ?? 'Pelanggan';
        $trackingUrl = url('/user-panel?ticket=' . $order->ticket_no);
        $courier = $order->courier_name ?? 'Kurir Workshop';
        $receiver = $order->receiver_name ?? $customerName;

        $waMessage = "Halo {$customerName}, unit servis dengan tiket *{$order->ticket_no}* telah diserahkan oleh kurir *{$courier}* kepada *{$receiver}* dengan tanda tangan digital resmi.\n\n"
            . "⏱️ Timer uji toleransi 1x24 jam dimulai sekarang.\n"
            . "Silakan uji coba unit Anda. Faktur invoice & garansi 90 hari dapat dicek di:\n{$trackingUrl}\n\n"
            . "Terima kasih telah menggunakan SerComp!";

        self::dispatchEmail(
            $order,
            "Unit Telah Diserahkan Oleh Kurir",
            "Bukti Serah Terima Unit Telah Terbit",
            "Unit Anda telah diserahterimakan oleh kurir {$courier} kepada {$receiver}. Bukti serah terima dan tanda tangan digital telah tersimpan di sistem."
        );

        self::logWaDispatch($order, $waMessage);

        return [
            'wa_message' => $waMessage,
            'wa_link' => self::buildWaLink($order->customer?->phone ?? '', $waMessage),
        ];
    }
}
