const URL = "http://localhost/codes/Simulacro/uploads/";
const sideBar = document.querySelector("#menuBar");
const sideBar2 = document.querySelector("#createBar");

let html = "";
let campos = "";

async function logJSONData() {
  const response = await fetch(`${URL}campus`);
  const jsonData = await response.json();
  jsonData.Message.forEach((element) => {
    let nameLink = element.TABLE_NAME.replace(/_/g, " ");
    html += `<li><a href="#" class="a" data-name="${element.TABLE_NAME}">${nameLink}</a></li>`;
  });
  sideBar.innerHTML = html;
  sideBar2.innerHTML = html;
}
let cardsHTML = "";

/*GET ALL Y DEMÁSSSSSSSSSSSSSSSSSSSSSSSSSSS :( ESTO ESTÁ HORRIBLE... */
const containerCards = document.querySelector("#containerCards");
sideBar.addEventListener("click", (e) => {
  e.preventDefault();
  const PRESS = e.target;
  console.log(PRESS.className);
  if (PRESS.className === "a") {
    document.querySelector("#title").innerHTML = PRESS.dataset.name.replace(
      /_/g,
      " "
    );
    async function cards() {
      const response = await fetch(`${URL}${PRESS.dataset.name}`);
      const jsonData = await response.json();
      console.log(jsonData);
      campos = jsonData.Campos;
      jsonData.Message.forEach((element) => {
        cardsHTML += `
        <div class="card">
        <div class="card-header">
        <h3 class="card-title">Registro ${element.id}</h3>
        <div class="card-tools">
        <!-- Buttons, labels, and many other things can be placed here! -->
        <!-- Here is a label for example -->
        </div>
        <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <form id="card-${element.id}">`;
        for (var clave in element) {
          if (element.hasOwnProperty(clave)) {
            cardsHTML += "<label>" + clave + "</label>";
            cardsHTML +=
              '<input type="text" value="' + element[clave] + '" readonly><br>';
          }
        }
        cardsHTML += `</form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer" id="footer">
        <button class="button-1" role="button" id="actualizar-${element.id}"data-action="actualizar" data-id="${element.id}">Actualizar</button>
        <button class="button-1" role="button" id="guardar-${element.id}" data-action="guardar" data-id="${element.id}" style="display:none">Guardar</button>
        <button class="button-1" role="button" data-action="eliminar" data-id="${element.id}" >Eliminar</button>
        </div>
        <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        `;
      });
      containerCards.innerHTML = cardsHTML;
      cardsHTML = "";

      const BUTTONS_GROUP = document.querySelectorAll("#footer");
      function updateCard(id) {
        const INPUT = document.querySelectorAll(`#card-${id} input`);
        INPUT.forEach((element, i) => {
          if (i !== 0 && i < campos.length) {
            element.removeAttribute("readonly");
          }
        });
        document.querySelector(`#guardar-${id}`).style.display = "flex";
        document.querySelector(`#actualizar-${id}`).style.display = "none";
      }
      BUTTONS_GROUP.forEach((element) => {
        element.addEventListener("click", (e) => {
          e.preventDefault();
          if (e.target.className === "button-1") {
            let id = "";
            let options = "";
            switch (e.target.dataset.action) {
              case "actualizar":
                id = e.target.dataset.id;
                updateCard(id);
                alert(
                  "Se habilitó el registro " +
                    id +
                    ". Recuerde darle en el botón guardar, de lo contrario no se registrarán cambios"
                );
                console.log(e.target.dataset.action);
                break;
              case "guardar":
                id = e.target.dataset.id;
                const INPUT = document.querySelectorAll(`#card-${id} input`);
                let data = {};
                for (let i = 0; i < campos.length; i++) {
                  if (i > 0 && i <= campos.length) {
                    data[campos[i]] = INPUT[i].value;
                  }
                }
                options = {
                  method: "PUT",
                  headers: {
                    "Content-Type": "application/json",
                  },
                  body: JSON.stringify(data),
                };
                fetch(`${URL}${PRESS.dataset.name}/${id}`, options)
                  .then((response) => {
                    if (response.ok) {
                      return response.json();
                    } else {
                      console.log(response);
                      throw new Error(response.statusText);
                    }
                  })
                  .then((data) => {
                    switch (data.Code) {
                      case 201:
                        alert(
                          "El recurso ha sido actualizado correctamente. Por favor recargue la página para ver los cambios"
                        );
                        location.reload();
                        break;
                      case 404:
                        alert("No hizo ningún cambio");
                        break;
                      case "23000":
                        alert(`${data.Message}`);
                        break;
                      default:
                        console.log("jola");
                        break;
                    }
                  })
                  .catch((error) => {
                    console.log(error);
                    console.error("Error:", error);
                    alert("Error al actualizar el recurso: " + error.Message);
                  });
                break;
              case "eliminar":
                id = e.target.dataset.id;
                options = {
                  method: "DELETE",
                };
                fetch(`${URL}${PRESS.dataset.name}/${id}`, options)
                  .then((response) => {
                    if (response.ok) {
                      console.log(response);
                      return response.json();
                    } else {
                      console.log(response);
                      throw new Error(response.statusText);
                    }
                  })
                  .then((data) => {
                    console.log(data);
                    switch (data.Code) {
                      case 201:
                        alert("Se ha eliminado el registro correctamente");
                        location.reload();
                        break;
                      case "23000":
                        alert(data.Message);

                      default:
                        break;
                    }
                  })
                  .catch((error) => {
                    console.error("Error:", error);
                    alert("Error al eliminar el recurso: " + error.message);
                  });
                console.log(e.target.dataset.id);
                break;
            }
          }
          e.stopImmediatePropagation();
        });
      });
    }
    cards();
  }
});

/************************************************************ */
sideBar2.addEventListener("click", (e) => {
  e.preventDefault();
  const PRESS = e.target;
  console.log(PRESS.className);
  if (PRESS.className === "a") {
    document.querySelector("#title").innerHTML = PRESS.dataset.name.replace(
      /_/g,
      " "
    );
    async function cards() {
      const response = await fetch(`${URL}${PRESS.dataset.name}`);
      const jsonData = await response.json();
      console.log(jsonData);
      campos = jsonData.Campos;
      cardsHTML += `
        <div class="card">
        <div class="card-header">
        <h3 class="card-title">Nuevo registro</h3>
        <div class="card-tools">
        <!-- Buttons, labels, and many other things can be placed here! -->
        <!-- Here is a label for example -->
        </div>
        <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <form id="card-new">`;
      for (let i = 0; i < campos.length; i++) {
        if (i > 0) {
          cardsHTML += "<label>" + campos[i] + "</label>";
          cardsHTML += '<input type="text"><br>';
        }
      }
      cardsHTML += `</form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer" id="footer">
        <button class="button-1" role="button" id="guardar-nuevo" data-action="guardar">Guardar</button>
        </div>
        <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        `;
      containerCards.innerHTML = cardsHTML;
      cardsHTML = "";
      let save = document.getElementById("guardar-nuevo");
      save.addEventListener("click", (e) => {
        e.preventDefault();
        const INPUTS = document.querySelectorAll("#card-new input");
        console.log(INPUTS);
        let data = {};
        for (let i = 0; i < campos.length - 1; i++) {
          console.log(i);
          if (i > 0 && i <= campos.length) {
            if (INPUTS[i].value !== "") {
              data[campos[i]] = INPUTS[i].value;
            } else {
              alert("Por favor complete los datos");
            }
          }
        }
        let options = {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        };
        fetch(`${URL}${PRESS.dataset.name}`, options)
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              console.log(response);
              throw new Error(response.statusText);
            }
          })
          .then((data) => {
            console.log(data);
            switch (data.Code) {
              case 201:
                alert("Dato agregado correctamente");
                break;
              case "23000":
                alert("La llave foranea ingresada no existe");
            }
          })
          .catch((error) => {
            console.log(JSON.stringify(error));
            console.error("Error:", error);
            alert("Error al agregar el recurso: " + error.Message);
          });
        e.stopImmediatePropagation();
      });
    }
    cards();
  }

  e.stopImmediatePropagation();
});

logJSONData();
