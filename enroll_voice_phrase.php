<?php
session_start();
include 'config/db.php'; // Asegúrate de que este archivo conecta a tu base de datos

// --- Seguridad: Asegúrate de que solo usuarios logueados puedan acceder a esta página ---
if (!isset($_SESSION["telefono"])) {
    // Si el usuario no está logueado, redirigir al login
    header("Location: login.php");
    exit();
}

$user_telefono = $_SESSION["telefono"]; // Obtenemos el teléfono del usuario logueado
$message = ""; // Para mensajes al usuario (éxito o error)

// --- Lógica de procesamiento de la frase de voz (AJAX POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transcribed_phrase'])) {
    header('Content-Type: application/json'); // Indicamos que la respuesta será JSON

    $transcribed_phrase = trim(mb_strtolower($_POST['transcribed_phrase'])); // Limpiamos y convertimos a minúsculas

    if (!empty($transcribed_phrase)) {
        // Guardar la frase transcrita en la base de datos para el usuario actual
        $stmt_update = $conn->prepare("UPDATE clientes SET voz_frase_secreta = ? WHERE telefono = ?");
        $stmt_update->bind_param("ss", $transcribed_phrase, $user_telefono); // 'ss' para dos strings
        
        if ($stmt_update->execute()) {
            echo json_encode(['success' => true, 'message' => 'Frase de voz registrada exitosamente! Frase: "' . htmlspecialchars($transcribed_phrase) . '"']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la frase en la base de datos.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se recibió una frase de voz válida. Intenta de nuevo.']);
    }
    exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
}
// --- Fin Lógica de procesamiento ---

// --- HTML para la página de registro de frase de voz ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Frase de Voz - Fidelización</title>
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
        .voice-enroll-card {
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            overflow: hidden;
        }
        .voice-enroll-card .card-header {
            background-color: #fff;
            text-align: center;
            padding: 2rem 1rem 1rem;
            border-bottom: none;
        }
        .voice-enroll-card .card-body {
            background-color: #f8f9fa;
            padding: 2rem;
        }
        .brand-icon {
            font-size: 3rem;
            color: #764ba2;
        }
        .btn-action {
            background-color: #007bff; /* Azul para iniciar */
            border-color: #007bff;
            color: white;
        }
        .btn-action:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-stop {
            background-color: #dc3545; /* Rojo para detener */
            border-color: #dc3545;
            color: white;
        }
        .btn-stop:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-save {
            background-color: #28a745; /* Verde para guardar */
            border-color: #28a745;
            color: white;
        }
        .btn-save:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-action:disabled, .btn-stop:disabled, .btn-save:disabled {
            opacity: 0.6;
        }
    </style>
</head>
<body>
<div class="card shadow voice-enroll-card">
    <div class="card-header">
        <div class="brand-icon mb-2">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <h4 class="mb-0">Registrar tu Frase de Voz</h4>
    </div>
    <div class="card-body">
        <p class="text-center">¡Hola, **<?= htmlspecialchars($user_telefono) ?>**!</p>
        <p class="text-center">Para usar la autenticación por voz, por favor di una frase secreta y clara (ej. "Mi voz es mi contraseña") y grábala. Asegúrate de pronunciarla igual cada vez.</p>
        <p class="text-muted text-center"><small>La transcripción se procesará en tu navegador.</small></p>
        
        <div class="text-center mb-3">
            <button id="startRecognition" class="btn btn-action me-2"><i class="bi bi-mic-fill"></i> Iniciar Grabación</button>
            <button id="stopRecognition" class="btn btn-stop" disabled><i class="bi bi-stop-circle-fill"></i> Detener Grabación</button>
            <p id="status" class="mt-3 text-muted">Presiona "Iniciar Grabación" y di tu frase secreta.</p>
        </div>
        <button id="savePhrase" class="btn btn-save w-100" disabled><i class="bi bi-save"></i> Guardar Frase de Voz</button>
        
        <div class="mt-3 text-center">
            <a href="index.php" class="btn btn-outline-secondary btn-sm">Volver al Inicio (sin guardar)</a>
        </div>
    </div>
</div>

<script>
    const startRecognitionButton = document.getElementById('startRecognition');
    const stopRecognitionButton = document.getElementById('stopRecognition');
    const savePhraseButton = document.getElementById('savePhrase');
    const statusParagraph = document.getElementById('status');
    
    let recognition; // Variable para el objeto SpeechRecognition
    let final_transcript = ''; // Para almacenar el resultado final de la transcripción

    // --- Verificar compatibilidad del navegador con Web Speech API ---
    if ('webkitSpeechRecognition' in window) { // 'webkitSpeechRecognition' es el prefijo para Chrome/Edge
        recognition = new webkitSpeechRecognition();
        recognition.continuous = false; // Queremos una sola frase, no un dictado continuo
        recognition.interimResults = true; // Para mostrar la transcripción en tiempo real (resultados interinos)
        recognition.lang = 'es-MX'; // Establece el idioma. Ajusta si tu público usa otro.

        // --- Eventos del Recognition Object ---
        recognition.onstart = () => {
            statusParagraph.textContent = 'Escuchando... di tu frase secreta ahora.';
            startRecognitionButton.disabled = true;
            stopRecognitionButton.disabled = false;
            savePhraseButton.disabled = true;
            final_transcript = ''; // Limpiar cualquier transcripción anterior
        };

        recognition.onresult = event => {
            let interim_transcript = '';
            // Recorre los resultados para construir la transcripción
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    final_transcript += event.results[i][0].transcript;
                } else {
                    interim_transcript += event.results[i][0].transcript;
                }
            }
            // Muestra lo que se está transcribiendo y el resultado final hasta ahora
            statusParagraph.textContent = 'Transcribiendo: ' + interim_transcript + ' (Final: ' + final_transcript + ')';
        };

        recognition.onerror = event => {
            console.error('Error de reconocimiento de voz:', event.error);
            statusParagraph.textContent = `Error de reconocimiento: ${event.error}. Asegúrate de dar permisos al micrófono y que haya silencio.`;
            startRecognitionButton.disabled = false;
            stopRecognitionButton.disabled = true;
            savePhraseButton.disabled = true;
        };

        recognition.onend = () => {
            // Cuando el reconocimiento termina
            statusParagraph.textContent = 'Reconocimiento finalizado. Frase detectada: "' + final_transcript + '".';
            if (final_transcript.length > 0) {
                savePhraseButton.disabled = false; // Habilitar el botón de guardar si hay texto
            }
            startRecognitionButton.disabled = false;
            stopRecognitionButton.disabled = true;
        };

        // --- Manejadores de Eventos para los Botones ---
        startRecognitionButton.addEventListener('click', () => {
            recognition.start(); // Inicia el reconocimiento de voz
        });

        stopRecognitionButton.addEventListener('click', () => {
            if (recognition && recognition.recognizing) {
                recognition.stop(); // Detiene el reconocimiento
            }
        });

        savePhraseButton.addEventListener('click', async () => {
            if (final_transcript.length === 0) {
                statusParagraph.textContent = 'Por favor, di una frase primero antes de intentar guardar.';
                return;
            }

            statusParagraph.textContent = 'Guardando frase...';
            savePhraseButton.disabled = true; // Deshabilitar para evitar múltiples envíos

            const formData = new FormData();
            formData.append('transcribed_phrase', final_transcript); // Enviamos el texto transcrito al PHP

            try {
                const response = await fetch('enroll_voice_phrase.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json(); // Esperamos una respuesta JSON del PHP

                if (result.success) {
                    statusParagraph.textContent = `¡Éxito! ${result.message}`;
                    // Aquí podrías redirigir al usuario, por ejemplo, al dashboard,
                    // o mantenerlo en la página con un mensaje de éxito persistente.
                    // window.location.href = 'index.php'; 
                } else {
                    statusParagraph.textContent = `Error al guardar: ${result.message}`;
                    savePhraseButton.disabled = false; // Re-habilitar para reintentar
                }
            } catch (error) {
                console.error('Error al enviar la frase:', error);
                statusParagraph.textContent = 'Error de conexión al servidor al intentar guardar.';
                savePhraseButton.disabled = false;
            }
        });

    } else {
        // --- Mensaje si el navegador no soporta la API ---
        statusParagraph.textContent = 'Lo siento, tu navegador no soporta la Web Speech API. Por favor, usa Chrome, Edge o un navegador compatible.';
        startRecognitionButton.disabled = true;
        stopRecognitionButton.disabled = true;
        savePhraseButton.disabled = true;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>