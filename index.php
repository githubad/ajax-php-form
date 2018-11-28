<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Asynchronous Form</title>
    <style>
      #result {
        display: none;
      }
      #spinner {
        display:none;
      }
    </style>
  </head>
  <body>

    <div id="measurements">
      <p>Enter measurements below to determine the total volume.</p>
      <form id="measurement-form" action="process_measurements.php" method="POST">
        Length: <input type="text" name="length" /><br />
        <br />
        Width: <input type="text" name="width" /><br />
        <br />
        Height: <input type="text" name="height" /><br />
        <br />
        <input id="html-submit" type="submit" value="Submit" />

      </form>
    </div>

      <div id="spinner">
        <img src="spinner.gif" alt="" width="50" height="50" />
      </div>

    <div id="result">
      <p>The total volume is: <span id="volume"></span></p>
    </div>

    <script type="text/javascript">

          var result_div = document.getElementById("result");
          var volume = document.getElementById("volume");

          function postResult(value) {
            volume.innerHTML = value;
            result_div.style.display = 'block';
          }

          function clearResult() {
            volume.innerHTML = '';
            result_div.style.display = 'none';
          }

          function showSpinner() {
            let spinner = document.querySelector('#spinner');
            spinner.style.display = "block";
            button.disabled = true;
            button.value = 'Loading...';


          }
          var button = document.getElementById("html-submit");
          button.addEventListener("click", (e) => {
            // working without the event default, but safe side for other browsers
            e.preventDefault();
            calculateMeasurements();
          });
          let button_value = button.value;

          function hideSpinner() {
            let spinner = document.querySelector('#spinner');
            spinner.style.display = "none";
            button.disabled = false;
            button.value = button_value;


          }

          function clearErrors() {
            let inputs = document.querySelectorAll('input');
            for ( i = 0; i < inputs.length; i++) {
              inputs[i].style.cssText = "";
            }
          }

          function displayErrors(errors) {
            let inputs = document.querySelectorAll('input');
            for ( i = 0; i < inputs.length; i++) {
              let input = inputs[i];
              if(errors.indexOf(input.name) >= 0) {
                input.style.cssText = "background:red; color:white";

              }
            }
          }



          let form = document.getElementById("measurement-form");

            // determine form action
            let action = form.getAttribute('action');

            // console.log(action);
            // omits textarea, select-options, checkboxes, radio buttons
            function gatherFormData() {
              let inputs = form.querySelectorAll('input');
              let array = [];
              for  (i = 0; i < inputs.length; i++) {
                let inputNameValue = inputs[i].name + '=' + inputs[i].value;
                array.push(inputNameValue);
              }
              // console.log(JSON.stringify(array.join('&')));
              return array.join('&');
            }

                        function calculateMeasurements() {
                          clearResult();
                          clearErrors();
                          showSpinner();
                          // gather form data
                          // let form_data  = gatherFormData();
                            let form_data = new FormData(form);

                            // for([key, value] of form_data.entries()) {
                            //   console.log(key + ":" + value);
                            // }


                              // let parent = this.parentElement;
                              // let json_upload = "id=" + parent.id;
                              fetch(action,
                                {
                                method:"POST",
                                headers: {
                                  // Not to use FormData with content type, used for gatherFormData
                                  // "Content-type":"application/x-www-form-urlencoded",
                                  "X-REQUESTED-WITH" : "XMLHttpRequest"
                                },
                                body: form_data,
                                credentials: 'same-origin' })
                              .then(response => response.json() )
                            .then(data => {
                              hideSpinner();
                              if (data.hasOwnProperty('errors') && data.errors.length > 0 ) {
                                displayErrors(data.errors);
                                console.log('Result ' + data.errors);
                              } else {

                                postResult(data.volume);
                              console.log('Result ' + data.volume);
                            }
                            });
                            // Will not show anything with FormData as its a object but with gatherFormData
                            // console.log(JSON.stringify(form_data));
                        }



    </script>

  </body>
</html>
