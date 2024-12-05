<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Buku Tamu</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .styled-select {
            width: 100%;
            max-width: 200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            background-image: linear-gradient(45deg, transparent 50%, #4CAF50 50%),
                linear-gradient(135deg, #4CAF50 50%, transparent 50%);
            background-position: calc(100% - 20px) calc(1em), calc(100% - 15px) calc(1em);
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            font-size: 16px;
            color: #333;
        }

        .styled-select:hover {
            border-color: #45a049;
        }

        .styled-select:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(72, 239, 128, 0.5);
        }


        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        canvas {
            border: 1px solid #ccc;
            display: block;
            margin: 10px 0;
        }

        .corner-image {
            width: 50px;
            position: absolute;
        }

        .left {
            top: 10px;
            left: 10px;
        }

        .right {
            top: 10px;
            right: 10px;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 90%;
            }

            .corner-image {
                width: 40px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/bpsdmp.png') }}" class="corner-image left">
        <img src="{{ asset('assets/utm.png') }}" class="corner-image right">

        <h2>Buku Tamu</h2>
        <form id="wizard-form" action="{{ route('menu.store') }}" method="POST" onsubmit="return validateForm()">

            <div class="step active" id="step-1">
                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label>Nomer HP</label>
                    <div style="display: flex; align-items: center;">
                        <input type="text" id="nomer_hp" name="nomer_hp" required oninput="validatePhoneNumber()" placeholder="81234567"pattern="\d*"
                            style="flex: 2; height: 40px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-right: 10px; box-sizing: border-box;">
                        <select id="country_code" name="country_code"
                            style="flex: 1; height: 40px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"
                            required>
                            @include('Select')
                        </select>
                    </div>
                </div>
                <div>
                    <label>Keperluan/Tujuan</label>
                    <input type="text" id="keperluan" name="keperluan" required>
                </div>
                <div>
                    <label>Instansi</label>
                    <input type="text" id="instansi" name="instansi" required>
                </div>
                <button type="button" onclick="nextStep()">Berikutnya</button>
            </div>

            <div class="step" id="step-2">
                <h3>Tanda Tangan Digital</h3>
                <canvas id="signature-pad" width="500" height="200"></canvas>
                <button type="button" id="clear-button">Hapus Tanda Tangan</button>
                <button type="button" onclick="prevStep()">Sebelumnya</button>
                <button type="button" onclick="submitForm()">Selesai</button>
                <input type="hidden" name="signature" id="signature-data">
            </div>
        </form>

    <script>
        let currentStep = 1;

        function nextStep() {
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.add('active');
        }

        function prevStep() {
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            currentStep--;
            document.getElementById(`step-${currentStep}`).classList.add('active');
        }

        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (isDrawing) {
                ctx.lineTo(e.offsetX, e.offsetY);
                ctx.stroke();
            }
        });

        canvas.addEventListener('mouseup', () => {
            isDrawing = false;
        });

        document.getElementById('clear-button').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        function submitForm() {
            const dataURL = canvas.toDataURL();
            document.getElementById('signature-data').value = dataURL;
            document.getElementById('wizard-form').submit();
        }

        $(document).ready(function() {
            $('#country_code').select2({
                placeholder: "Pilih kode negara",
                width: '100%'
            });
        });

        // Validate form inputs
        function validateForm() {
            const namaLengkap = document.getElementById('nama_lengkap').value;
            const email = document.getElementById('email').value;
            const nomerHp = document.getElementById('nomer_hp').value;
            const keperluan = document.getElementById('keperluan').value;
            const instansi = document.getElementById('instansi').value;

            // Check if all fields are filled
            if (!namaLengkap || !email || !nomerHp || !keperluan || !instansi) {
                alert('Semua field harus diisi!');
                return false;
            }

            if (!email.includes('@')) {
                alert('Email harus mengandung karakter "@"');
                return false;
            }

            return true;
        }

        // Validate phone number input to only allow digits
        function validatePhoneNumber() {
            const phoneInput = document.getElementById('nomer_hp');
            phoneInput.value = phoneInput.value.replace(/\D/g, ''); // Remove non-numeric characters

            // Jika angka pertama adalah 0, hapus angka tersebut
            if (phoneInput.value.startsWith('0')) {
                phoneInput.value = phoneInput.value.substring(1);
            }

        }

    </script>

    <div class="background-video">
        <video autoplay muted loop>
            <source src="{{ asset('assets/bgdepan.mp4') }}" type="video/mp4">
        </video>
    </div>
</body>

</html>
