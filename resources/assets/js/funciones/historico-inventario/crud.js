// si existe el elemento con id "buscar" se agrega que cada que se escribe apartir de la segunda letra
var estado_alerta = true;
console.log("ruta", ruta);
function bfdsdfsdfuscar() {
  console.log("ruta", ruta);

}
if (document.getElementById("buscar")) {
  let condicion_anterior = "";
  document.getElementById("buscar").addEventListener("keyup", (event) => {
    event.preventDefault();
    let condicion = "";
    let condicion_anterior = "";
    let se_puede_buscar = false;
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
      fetch(`${window.baseUrl}proceso/${window.route}/buscar`, {
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
  const ruta_web = `${document.documentElement.getAttribute('data-assets-path')}/proceso/${ruta}`;

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
          // y si es "visible" se muestra icono de ojo y si no icono de ojo tachado
        } else if (encabezado == "visible") {
          if (item[encabezado] == 1) {
            td.innerHTML = '<i class="ti ti-eye ti-sm"></i>';
          } else if (item[encabezado] == 0) {
            td.innerHTML = '<i class="ti ti-eye-off ti-sm"></i>';
          }

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
      // tr.appendChild(tdAcciones);

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
// funcion para recuperar registro
function addEventRecuperarRegistro() {
  if (document.querySelectorAll(".recuperar")) {
    let elementos = document.querySelectorAll(".recuperar");
    elementos.forEach(elemento => {
      elemento.addEventListener("click", (e) => {
        e.preventDefault();
        recuperar(elemento.getAttribute("data-id"));
      });
    });
  }
}

// ejecutar la funcion al cargar el dom
document.addEventListener('DOMContentLoaded', addEventBorrarRegistro);
document.addEventListener('DOMContentLoaded', addEventRecuperarRegistro);

function borrar(id) {
  // sweetalert2
  Swal.fire({
    title: '¿Estas seguro?',
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
      fetch(`${window.baseUrl}proceso/${window.route}/${id}`, {
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
                window.location.href= window.location.href
              }
            });
          }
          else {
            // se muestra una alerta
            Swal.fire({
              title: "Error",
              text: data.message ?? data.mensaje,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-danger'
              },
              buttonsStyling: false,
            });
          }
        });
    }
  })
}
// recuperar registro en softdelete
function recuperar(id) {
  // sweetalert2
  Swal.fire({
    title: '¿Estas seguro?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Si, recuperar',
    cancelButtonText: 'No, cancelar',
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-outline-danger'
    },
    buttonsStyling: false,
    // cuando se da click en el boton de confirmar
    preConfirm: () => {
      // se envia la peticion
      fetch(`${window.baseUrl}proceso/${window.route}/restore/${id}`, {
        method: "POST",
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
              title: "Recuperado",
              text: data.message,
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false,
            }).then((result) => {
              if (result.isConfirmed) {
                // redirecciona recargar la pagina
                window.location.href= window.location.href
              }
            });
          }
          else {
            // se muestra una alerta
            Swal.fire({
              title: "Error",
              text: data.message ?? data.mensaje,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-danger'
              },
              buttonsStyling: false,
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
      // agregar dropzoneMulti al formData se encuentra en array por dropzone
      dropzoneMulti.forEach((item, index) => {
        // console.log(item);
        // console.log(item.files);
        // console.log(item.files.length);
        if (item.files.length > 0) {
          item.files.forEach((itemFile, indexFile) => {
            // console.log(itemFile);
            // console.log(itemFile.name);
            // console.log(itemFile.size);
            formData.append(`dropzoneMulti[${index}][${indexFile}]`, itemFile);
          });
        }
      });

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
            formulario.querySelector('button[type="submit"]').disabled = false;
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
            // habilitar boton de submit
            formulario.querySelector('button[type="submit"]').disabled = false;
          }
        })
        .catch(error => {
          // habilitar boton de submit
          formulario.querySelector('button[type="submit"]').disabled = false;
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
  $(".select_modelo").select2({
    placeholder: 'Selecciona opciones',
  });
});

$(document).ready(function () {
  let repeater = $('.repeater').repeater({
    defaultValues: {
      'text-input': 'foo'
      // text area
    },
    show: function () {
      $(this).slideDown();
      // borrar elemento
      jQuery(this.querySelector(".contenedor-imagen-modelo")).remove();
      // scroll al agregar un nuevo elemento
      $('html, body').animate({
        scrollTop: $(this).offset().top
      }, 500);
      // poner el foco en el primer input del nuevo elemento
      $(this).find('input').eq(0).focus();
      inicializarDropZone(this);
    },
    repeaters: [{
      selector: '.inner-repeater',

      defaultValues: {
        'text-input': 'foo'
        // text area
      },
      show: function () {
        // remover '.select2-container'
        $(this).find(".select2-container").remove();
        // aplicar $(".select2").select2(); a los select que estan en this
        $(".select_modelo").select2({
          placeholder: "Selecciona una opción",
          allowClear: true,
          width: '100%'
        });
        $(this).slideDown();

      },
    }],
    // Este evento se dispara cuando se añade un nuevo elemento
    repeat: function () {
      // Encuentra todos los campos de entrada en el nuevo elemento y los vacía
      this.find('input').val('');
      console.log(this);
      // poner el foco en el primer input del nuevo elemento
      $(this).find('input').eq(0).focus();
    },
    hide: function (deleteElement) {
      borrarModelo(this, deleteElement);

    }
  });
  inicializarDropZone();
});
var dropzoneMulti = [];
function inicializarDropZone(selector_padre) {
  const previewTemplate = `<div class="dz-preview dz-file-preview col-6">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No vista previa</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;
  let dsad = ".dropzone-multi";
  let padre = (selector_padre) ? selector_padre : document;
  if (padre.querySelector(dsad)) {
    let elementos = padre.querySelectorAll(dsad);
    elementos.forEach(elemento => {
      // modelos[][imagenes]
      let element = new Dropzone(elemento, {
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 5,
        addRemoveLinks: true,
        acceptedFiles: "image/*",
      });
      // se agrega el nombre del input
      dropzoneMulti.push(element);
    });
  } else {
    console.log("no existe");
  }
}

function addEventBorrarImagenModelo() {
  if (document.querySelectorAll(".borrarImagenModelo")) {
    let elementos = document.querySelectorAll(".borrarImagenModelo");
    elementos.forEach(elemento => {
      elemento.addEventListener("click", (e) => {
        e.preventDefault();
        borrarImagenModelo(elemento.getAttribute("data-id"));
      });
    });
  }
}

// ejecutar la funcion al cargar el dom
document.addEventListener('DOMContentLoaded', addEventBorrarImagenModelo);
