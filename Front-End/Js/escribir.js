let texto = "Bienvenido a Gestor Académico";
let i = 0;
function escribirT() {
    if (i < texto.length) {
        document.getElementById("escribir").textContent += texto[i++];
        setTimeout(escribirT, 100);
    }
}
escribirT();