async function seleccionarContacto() {
  if (!("contacts" in navigator) || !("ContactsManager" in window)) {
    alert("Tu navegador no soporta la API de Contactos.");
    return;
  }

  try {
    const props = ["name", "tel", "email"];
    const opts = { multiple: false };

    const contactos = await navigator.contacts.select(props, opts);

    if (contactos.length > 0) {
      const contacto = contactos[0];
      document.getElementById("resultadoContacto").innerHTML = `
        <strong>Nombre:</strong> ${contacto.name}<br>
        <strong>Teléfono:</strong> ${contacto.tel}<br>
        <strong>Correo:</strong> ${contacto.email || 'No disponible'}
      `;
    }
  } catch (err) {
    console.error("Error al seleccionar contacto:", err);
  }
}
