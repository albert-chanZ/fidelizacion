<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION["2fa_pending_telefono"])) {
    header("Location: login.php");
    exit();
}

$telefono_to_verify = $_SESSION["2fa_pending_telefono"];
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transcribed_phrase'])) {
    header('Content-Type: application/json');

    $transcribed_phrase_attempt = trim(mb_strtolower($_POST['transcribed_phrase']));

    // 1. Obtener la frase secreta de voz del usuario desde la base de datos
    $stmt_phrase = $conn->prepare("SELECT voz_frase_secreta FROM clientes WHERE telefono = ?");
    $stmt_phrase->bind_param("s", $telefono_to_verify);
    $stmt_phrase->execute();
    $result_phrase = $stmt_phrase->get_result();
    $user_data = $result_phrase->fetch_assoc();

    if ($user_data && !empty($user_data['voz_frase_secreta'])) {
        $stored_voice_phrase = trim(mb_strtolower($user_data['voz_frase_secreta']));

        // Comparación de la frase
        if ($transcribed_phrase_attempt === $stored_voice_phrase) {
            // Autenticación por voz exitosa. Limpiar variables 2FA y establecer sesión final.
            unset($_SESSION["2fa_pending_telefono"]);
            unset($_SESSION["2fa_pending_id"]);

            $stmt_final_login = $conn->prepare("SELECT * FROM clientes WHERE telefono = ?");
            $stmt_final_login->bind_param("s", $telefono_to_verify);
            $stmt_final_login->execute();
            $result_final_login = $stmt_final_login->get_result();
            $user_final = $result_final_login->fetch_assoc();

            if ($user_final) {
                $_SESSION["telefono"] = $user_final["telefono"];
                $_SESSION["tipo"] = ($user_final["telefono"] == "admin") ? "admin" : "cliente";
                echo json_encode(['success' => true, 'message' => 'Autenticación por voz exitosa!']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error interno al cargar los datos del usuario.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Frase de voz incorrecta. Inténtalo de nuevo.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Este usuario no tiene una frase de voz registrada.']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación por Voz - Fidelización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .voice-card {
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            overflow: hidden;
        }
        .voice-card .card-header {
            background-color: #fff;
            text-align: center;
            padding: 2rem 1rem 1rem;
            border-bottom: none;
        }
        .voice-card .card-body {
            background-color: #f8f9fa;
            padding: 2rem;
        }
        .brand-icon {
            font-size: 3rem;
            color: #764ba2;
        }
        .btn-voice {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-voice:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-voice:disabled {
            opacity: 0.6;
        }
    </style>
</head>
<body>
<div class="card shadow voice-card">
    <div class="card-header">
        <div class="brand-icon mb-2">
            <i class="bi bi-mic-fill"></i>
        </div>
        <h4 class="mb-0">Verificación de Voz</h4>
    </div>
    <div class="card-body">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?= $error_message ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?= $success_message ?></div>
        <?php endif; ?>
        
        <p class="text-center lead">Hola, **<?= htmlspecialchars($telefono_to_verify) ?>**.</p>
        <p class="text-center">Por favor, di tu frase secreta de voz para completar la autenticación.</p>

        <div class="text-center mb-3">
            <button id="startRecognition" class="btn btn-info btn-voice me-2"><i class="bi bi-mic-fill"></i> Iniciar Reconocimiento</button>
            <button id="stopRecognition" class="btn btn-warning btn-voice" disabled><i class="bi bi-stop-circle-fill"></i> Detener Reconocimiento</button>
            <p id="voiceStatus" class="mt-2 text-muted">Presiona "Iniciar Reconocimiento" para empezar.</p>
        </div>
        <button id="verifyVoiceBtn" class="btn btn-success w-100" disabled><i class="bi bi-person-vcard"></i> Verificar Voz</button>
        
        <div class="mt-3 text-center">
            <a href="logout.php" class="btn btn-outline-secondary btn-sm">Cancelar y Volver al Login</a>
        </div>
    </div>
</div>

<script>
    const startRecognitionButton = document.getElementById('startRecognition');
    const stopRecognitionButton = document.getElementById('stopRecognition');
    const verifyVoiceButton = document.getElementById('verifyVoiceBtn');
    const voiceStatusParagraph = document.getElementById('voiceStatus');
    
    let recognition;
    let final_transcript = '';

    if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = true;
        recognition.lang = 'es-MX';

        recognition.onstart = () => {
            voiceStatusParagraph.textContent = 'Escuchando... di tu frase secreta.';
            startRecognitionButton.disabled = true;
            stopRecognitionButton.disabled = false;
            verifyVoiceButton.disabled = true;
            final_transcript = '';
        };

        recognition.onresult = event => {
            let interim_transcript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    final_transcript += event.results[i][0].transcript;
                } else {
                    interim_transcript += event.results[i][0].transcript;
                }
            }
            voiceStatusParagraph.textContent = 'Transcribiendo: ' + interim_transcript + ' (Final: ' + final_transcript + ')';
        };

        recognition.onerror = event => {
            console.error('Error de reconocimiento de voz:', event.error);
            voiceStatusParagraph.textContent = `Error de reconocimiento: ${event.error}. Intenta de nuevo.`;
            startRecognitionButton.disabled = false;
            stopRecognitionButton.disabled = true;
            verifyVoiceButton.disabled = true;
        };

        recognition.onend = () => {
            voiceStatusParagraph.textContent = 'Reconocimiento finalizado. Frase detectada: "' + final_transcript + '".';
            if (final_transcript.length > 0) {
                verifyVoiceButton.disabled = false;
            }
            startRecognitionButton.disabled = false;
            stopRecognitionButton.disabled = true;
        };

        startRecognitionButton.addEventListener('click', () => {
            recognition.start();
        });

        stopRecognitionButton.addEventListener('click', () => {
            if (recognition && recognition.recognizing) {
                recognition.stop();
            }
        });

        verifyVoiceButton.addEventListener('click', async () => {
            if (final_transcript.length === 0) {
                voiceStatusParagraph.textContent = 'Por favor, di tu frase secreta.';
                return;
            }

            voiceStatusParagraph.textContent = 'Verificando frase...';
            verifyVoiceButton.disabled = true;

            const formData = new FormData();
            formData.append('transcribed_phrase', final_transcript);

            try {
                const response = await fetch('verify_voice.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    voiceStatusParagraph.textContent = '¡Autenticación por voz exitosa! Redirigiendo...';
                    window.location.href = 'index.php';
                } else {
                    voiceStatusParagraph.textContent = `Error: ${result.message}`;
                    verifyVoiceButton.disabled = false;
                }
            } catch (error) {
                console.error('Error al enviar la frase para verificación:', error);
                voiceStatusParagraph.textContent = 'Error de conexión o en la verificación. Intenta de nuevo.';
                verifyVoiceButton.disabled = false;
            }
        });

    } else {
        voiceStatusParagraph.textContent = 'Lo siento, tu navegador no soporta la Web Speech API.';
        startRecognitionButton.disabled = true;
        stopRecognitionButton.disabled = true;
        verifyVoiceButton.disabled = true;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>