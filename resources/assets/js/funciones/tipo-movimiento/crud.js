

// si existe el elemento con id "buscar" se agrega que cada que se escribe apartir de la segunda letra
var estado_alerta = true;
if (document.getElementById("buscar")) {
  let condicion_anterior = "";
  let se_puede_buscar = false;
  document.getElementById("buscar").addEventListener("keyup", (event) => {
    event.preventDefault();
    let condicion = "";
    // se buscara cuando se escriba mas de 3 letras y si es menor y la longitud anterior
    if (event.target.value.length > 3) {
      condicion = event.target.value;
      se_puede_buscar = true;
    } else if (event.target.value.length == 0 && condicion_anterior.length == 1) {
      condicion = event.target.value;
      se_puede_buscar = true;
    } else {
      se_puede_buscar = false;
    }

    if (se_puede_buscar == true) {
      fetch(`${window.baseUrl}catalogos/${window.route}/buscar`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          _token: document.getElementsByName("_token")[0].value,
          condicion: event.target.value,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          renderizarHTML(data);
        })
        .catch((err) => console.log(err));
    }
    condicion_anterior = event.target.value;
  });
}

function renderizarHTML(jsonData) {
  const listado = jsonData.listado;
  const encabezados = jsonData.encabezados;
  const paginationHtml = jsonData.paginationHtml;
  const ruta_web = `${document.documentElement.getAttribute('data-assets-path')}/catalogos/${ruta}`;

  // tabla-{{$nombreMod}}
  const tablaClientes = document.getElementById(`tabla-${ruta}`);
  const paginado = document.getElementById(`paginado-${ruta}`);
  if (listado.data == undefined) {
    listado.data = [];
  }
  // Actualiza la tabla en caso de que existan datos en el listado y sea definido listado.data
  if (listado.data.length > 0) {
    const tabla = tablaClientes.querySelector("table");
    const tbody = tabla.querySelector("tbody");
    tbody.innerHTML = '';

    listado.data.forEach((item) => {

      const tr = document.createElement("tr");
      //agregar un id a registro
      tr.setAttribute("id", `tabla-registro-${item.id}`);
      encabezados.forEach((encabezado, index) => {
        const td = document.createElement("td");
        td.classList.add(index === 0 ? "d-table-cell" : "d-none", "d-lg-table-cell");
        // si el encabezado es igual a sinonimos y si existe
        if (encabezado == "sinonimos") {
          // converir a json '["boda", "casorio", "matrimonio", "boda al civil", "boda legal", "boda de plata", "boda de oro", "boda diamante", "2"]'
          let sinonimos = item[encabezado];
          // <span class="badge rounded-pill bg-primary">{{ $itemJson }}</span>
          let html_sinonimos = "";
          sinonimos.forEach(itemJson => {
            html_sinonimos += `<span class="badge my-1 me-1 rounded-pill bg-primary">${itemJson}</span>`;
          });
          td.innerHTML = html_sinonimos;
        } else {
          td.innerHTML = item[encabezado];
        }
        tr.appendChild(td);
      });

      const tdAcciones = document.createElement("td");
      const divAcciones = document.createElement("div");
      divAcciones.classList.add("d-flex", "align-items-center", "justify-content-center");

      const editarLink = document.createElement("a");
      editarLink.href = `${ruta_web}/${item.id}/edit`;
      editarLink.classList.add("text-body");
      editarLink.innerHTML = '<i class="ti ti-edit ti-sm me-2"></i>';

      const borrarLink = document.createElement("a");
      borrarLink.href = "javascript:void(0)";
      borrarLink.classList.add("text-body");
      borrarLink.setAttribute("onclick", `borrar(${item.id})`);
      borrarLink.innerHTML = '<i class="ti ti-trash ti-sm"></i>';

      divAcciones.appendChild(editarLink);
      divAcciones.appendChild(borrarLink);
      tdAcciones.appendChild(divAcciones);
      tr.appendChild(tdAcciones);

      tbody.appendChild(tr);
    });
  } else {
    // Si no hay datos en el listado, puedes mostrar un mensaje o realizar alguna acción específica
    // Puedes agregar código aquí para mostrar un mensaje o realizar alguna otra acción
    // Por ejemplo, puedes agregar un mensaje en el lugar de la tabla.
    if (estado_alerta == true) {
      // sweetalert2
      estado_alerta = false;
      Swal.fire({
        title: 'Atencion',
        icon: 'info',
        text: 'No hay datos para mostrar',
        type: 'warning',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false,
        // cuando se da click en el boton de confirmar estado_alerta cambia a false
        onAfterClose: () => {
          estado_alerta = true;
        },
      })
    }
  }
  // si listado.data es indefinido
  if (listado.data == undefined) {
    // Si no hay datos en el listado, puedes ocultar el paginado
    paginado.innerHTML = '';
  } else {
    // Si no hay datos en el listado, puedes ocultar el paginado
    paginado.innerHTML = paginationHtml;
  }
}
// function borrar
function addEventBorrarRegistro() {
  if (document.querySelectorAll(".borrar")) {
    let elementos = document.querySelectorAll(".borrar");
    elementos.forEach(elemento => {
      elemento.addEventListener("click", (e) => {
        e.preventDefault();
        borrar(elemento.getAttribute("data-id"));
      });
    });
  }
}

// ejecutar la funcion al cargar el dom
document.addEventListener('DOMContentLoaded', addEventBorrarRegistro);
function borrar(id) {
  // sweetalert2
  Swal.fire({
    title: '¿Estas seguro?',
    text: "¡No podras revertir esto!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Si, borrar',
    cancelButtonText: 'No, cancelar',
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-outline-danger'
    },
    buttonsStyling: false,
    // cuando se da click en el boton de confirmar
    preConfirm: () => {
      // se envia la peticion
      fetch(`${window.baseUrl}catalogos/${window.route}/${id}`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          _token: document.getElementsByName("_token")[0].value,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          // si la peticion es correcta
          if (data.error == false && (data.message || data.mensaje)) {
            // se quita el registo tabla-registro-${id}
            // document.getElementById(`tabla-registro-${id}`).remove();
            // se muestra una alerta
            Swal.fire({
              title: "Borrado",
              text: data.message,
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false,
            }).then((result) => {
              if (result.isConfirmed) {
                // redireccionar
                window.location.href = data.url_redirect;
              }
            });

          }
        });
    }
  })
}

var nombre_formulario = "formulario_crud";
if (document.getElementById(nombre_formulario)) {
  document.addEventListener('DOMContentLoaded', function () {
    // Espera a que el DOM esté completamente cargado

    // Obtén el formulario por su ID
    let formulario = document.getElementById(nombre_formulario);

    // Agrega un evento de escucha para el envío del formulario
    formulario.addEventListener('submit', function (event) {
      // Evita que el formulario se envíe de forma predeterminada
      event.preventDefault();
      // desabilitar boton de submit
      formulario.querySelector('button[type="submit"]').disabled = true;

      // Realiza tu lógica de validación aquí si es necesario

      // Obtiene los datos del formulario
      let formData = new FormData(formulario);

      // Realiza la solicitud AJAX para enviar los datos al servidor
      // Puedes usar la función fetch o XMLHttpRequest aquí
      fetch(formulario.action, {
        method: 'POST',
        body: formData,
        headers: {
          // Puedes añadir encabezados personalizados aquí si es necesario
        }
      })
        .then(response => response.json())
        .then(data => {
          // Realiza acciones adicionales después de enviar el formulario y recibir la respuesta
          // mensaje de exito si es 200 y si existe data.message
          if (data.error == false && data.message) {
            // se resetea el formulario
            Swal.fire({
              title: "Guardado",
              text: data.message,
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false,
            }).then((result) => {
              if (result.isConfirmed) {
                // redireccionar
                window.location.href = data.url_redirect;
              }
            });
          }
          if (data.error == true && data.message) {
            // si existe errors se recoren los campos y se muestra una lista de errores
            let html_errors = "";
            if (data.errors) {
              html_errors = "<ul>";
              for (const [key, value] of Object.entries(data.errors)) {
                html_errors += `<li>${value}</li>`;
              }
              html_errors += "</ul>";
            }
            // se resetea el formulario
            Swal.fire({
              title: "Error",
              html: `${data.message} ${html_errors}`,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-danger'
              },
              buttonsStyling: false,
            });
          }
        })
        .catch(error => {
          console.error('Error al enviar el formulario:', error);
        });
    });
  });
}

document.addEventListener('DOMContentLoaded', function () {
  // Inicializa el select con Select2
  let sinonimo = ".mySelect_sinonimos";
  if (document.querySelector(sinonimo)) {
    $(sinonimo).select2({
      tags: true, // Permite la creación de nuevas opciones
      createTag: function (params) {
        return {
          id: params.term,
          text: params.term,
          newTag: true // Indica que esta es una nueva opción
        };
      },
      tokenSeparators: [','], // Separadores de las nuevas opciones
      placeholder: 'Selecciona opciones',
    });
  }
});
