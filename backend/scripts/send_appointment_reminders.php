<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
$container = require dirname(__DIR__) . '/bootstrap/container.php';
$pdo = $container->get(PDO::class);
$appointments = $pdo->query("SELECT * FROM appointments WHERE status='scheduled' AND reminder_sent_at IS NULL AND appointment_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)")->fetchAll();
foreach ($appointments as $appointment) {
    foreach (['doctor_login_id', 'nurse_login_id'] as $login) {
        if (!$appointment[$login]) continue;
        $user = $pdo->prepare('SELECT id FROM users WHERE username=:username AND is_active=TRUE AND deleted_at IS NULL'); $user->execute(['username' => $appointment[$login]]);
        if ($id = $user->fetchColumn()) { $notice = $pdo->prepare('INSERT INTO notifications (user_id,type,title,message) VALUES (:user_id,:type,:title,:message)'); $notice->execute(['user_id'=>$id,'type'=>'appointment_reminder','title'=>'Appointment in 30 minutes','message'=>sprintf('%s is scheduled at %s (%s).',$appointment['patient_name'],$appointment['appointment_at'],$appointment['department_name'] ?? 'No department')]); }
    }
    $pdo->prepare('UPDATE appointments SET reminder_sent_at=CURRENT_TIMESTAMP WHERE id=:id')->execute(['id'=>$appointment['id']]);
}
echo "Sent reminders for " . count($appointments) . " appointment(s).\n";
