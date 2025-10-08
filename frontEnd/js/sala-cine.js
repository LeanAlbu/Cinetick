
document.addEventListener("DOMContentLoaded", () => {
  const fileiras = ["H","G","F","E","D","C","B","A"];
  const cadeirasPorFileira = 10;
  const ocupados = ["E4","E5","F3","F7"];  /*exemplo*/

  const container = document.getElementById("assentos-conteiner");
  if (!container) return;

  fileiras.forEach(fila => {
    const filaDiv = document.createElement("div");
    filaDiv.className = "fila";

    const label = document.createElement("span");
    label.className = "fila-label";
    label.textContent = fila;
    filaDiv.appendChild(label);

    const assentosDiv = document.createElement("div");
    assentosDiv.className = "assentos";

    for (let i = 1; i <= cadeirasPorFileira; i++) {
      const id = `${fila}${i}`;
      const seat = document.createElement("div");
      seat.className = "assento livre";
      seat.dataset.id = id;
      seat.textContent = i;

      if (ocupados.includes(id)) {
        seat.classList.remove("livre");
        seat.classList.add("ocupado");
      }

      seat.addEventListener("click", () => {
        if (seat.classList.contains("ocupado")) return;
        seat.classList.toggle("selecionado");
      });

      assentosDiv.appendChild(seat);
    }

    filaDiv.appendChild(assentosDiv);
    container.appendChild(filaDiv);
  });
});
