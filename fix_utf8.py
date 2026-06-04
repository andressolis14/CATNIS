import os

files = [
    r"c:\xampp\htdocs\CATNIS BAKERY\views\layout\header.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\ventas\recibo.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\errors\404.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\catalogo.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\cartelera.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\auth\restablecer.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\auth\olvido.php",
    r"c:\xampp\htdocs\CATNIS BAKERY\views\auth\login.php"
]

replacements = {
    "Ã³": "ó",
    "Ã±": "ñ",
    "Ã¡": "á",
    "Ã©": "é",
    "Ã­": "í",
    "Ãº": "ú",
    "Â¿": "¿",
    "Ã“": "Ó",
    "Ã‘": "Ñ",
    "â€¢": "•",
    "GestiÃ³n": "Gestión",
    "SesiÃ³n": "Sesión",
    "ANÃ LISIS": "ANÁLISIS",
    "electrÃ³nico": "electrónico",
    "ContraseÃ±a": "Contraseña"
}

for f in files:
    try:
        with open(f, 'r', encoding='utf-8') as file:
            content = file.read()
        for k, v in replacements.items():
            content = content.replace(k, v)
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f"Fixed {f}")
    except Exception as e:
        print(f"Error processing {f}: {e}")
