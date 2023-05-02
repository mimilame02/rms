
  document.getElementById("nextBtn").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      nextPrev(1);
    }
  });

  // Initialize the form wizard
  var currentTab = 0;
  showTab(currentTab);

    // Function to show the current step of the form wizard
  function showTab(stepIndex) {
    var steps = document.getElementsByClassName("tab");
    steps[stepIndex].style.display = "block";

    // Get all the span tags with class "step"
    var stepSpans = document.getElementsByClassName("step");

    // Loop through all the span tags and remove the "active" class from them
    for (var i = 0; i < stepSpans.length; i++) {
      stepSpans[i].classList.remove("active");
    }

    // Add the "active" class to the current step's span tag
    stepSpans[stepIndex].classList.add("active");

      if (stepIndex == 0) {
          document.getElementById("prevBtn").style.display = "none";
      } else {
          document.getElementById("prevBtn").style.display = "inline";
      }
      if (stepIndex == (steps.length - 1)) {
          document.getElementById("nextBtn").style.display = "none";
          document.getElementById("saveBtn").style.display = "inline";
      } else {
          document.getElementById("nextBtn").style.display = "inline";
          document.getElementById("saveBtn").style.display = "none";
      }
    }

    // Function to move to the next or previous step of the form wizard
    function nextPrev(n) {
  var x = document.getElementsByClassName("tab");
  if (n == 1 && !validateForm()) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Please fix the errors in the current step!'
    });
    return false;
  }
  x[currentTab].style.display = "none";
  currentTab = currentTab + n;
  if (currentTab >= x.length) {
    document.getElementById("regForm").submit();
    return false;
  }
  showTab(currentTab);
  }

  function validateForm() {
    let form = document.getElementById("regForm");
    let formData = new FormData(form);
    let x = document.getElementsByClassName("tab");
    let isValid = true;

    switch (currentTab) {
      case 0:
         /* Basic Details Step */
        if (!validateFName(form.elements["property_name"].value)) {
          updateValidInputClass(form.elements["property_name"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["property_name"], true);
        }

        if (!validateSelect(form.elements["landlord"].value)) {
          updateValidInputClass(form.elements["landlord"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["landlord"], true);
        }

        if (form.elements["num_of_floors"].value < 1) {
          updateValidInputClass(form.elements["num_of_floors"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["num_of_floors"], true);
        }
        break;

      case 1:
         /* Location Step */
        if (!validateSelect(form.elements["region"].value)) {
          updateValidInputClass(form.elements["region"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["region"], true);
        }

        if (!validateSelect(form.elements["provinces"].value)) {
          updateValidInputClass(form.elements["provinces"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["provinces"], true);
        }

        if (!validateSelect(form.elements["city"].value)) {
          updateValidInputClass(form.elements["city"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["city"], true);
        }

        if (!validateSelect(form.elements["barangay"].value)) {
          updateValidInputClass(form.elements["barangay"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["barangay"], true);
        }
        break;

      case 2:
         /* Features Step */
        const features = formData.getAll("features[]");
        if (features.length === 0) {
          form.elements["features[]"].forEach((feature) => {
            updateValidInputClass(feature, false);
          });
          isValid = false;
        } else {
          form.elements["features[]"].forEach((feature) => {
            updateValidInputClass(feature, true);
          });
        }
        break;

      case 3:
         /* Images Step */
        const image = formData.get("image_path");
        if (!image) {
          updateValidInputClass(form.elements["image_path"], false);
          isValid = false;
        } else {
          updateValidInputClass(form.elements["image_path"], true);
        }

        const numFloors = parseInt(form.elements["num_of_floors"].value);
        for (let i = 1; i <= numFloors; i++) {
          const floorPlan = formData.get(`floor_plan_${i}`);
          if (!floorPlan) {
            updateValidInputClass(form.elements[`floor_plan_${i}`], false);
            isValid = false;
          } else {
            updateValidInputClass(form.elements[`floor_plan_${i}`], true);
          }
        }
        break;
    }

    return isValid;
  }


  function updateValidInputClass(input, isValid) {
    if (isValid) {
      input.classList.add('is-valid');
      input.classList.remove('is-invalid');
    } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
    }
  }

  function handleKeyUp(event, element) {
    // Capitalize first letter of each sentence
    element.value = element.value.replace(/(^|\.\s+)([a-z])/g, function(match, p1, p2) {
      return p1 + p2.toUpperCase();
    });

    // Add a line break after each period when the period key is pressed
    if (event.key === '.') {
      setTimeout(function() {
        element.value = element.value.replace(/(\.)(?=[^\n])/g, '$1\n');
        element.selectionStart = element.selectionEnd;
      }, 0);
    }
  }

  function addLineBreaks(str) {
    return str.replace(/\.(\s|$)/g, '.\n');
  }
  function updateDescriptionWithLineBreaks() {
    const textareaP = document.getElementById('property_description');
    textareaP.value = addLineBreaks(textareaP.value);
    const textareaF = document.getElementById('features_description');
    textareaF.value = addLineBreaks(textareaF.value);
  }

  document.getElementById('property_description').addEventListener('input', updateDescriptionWithLineBreaks);
  document.getElementById('features_description').addEventListener('input', updateDescriptionWithLineBreaks);

  function validateFName(fname) {
    const namePattern = /^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/;
    return namePattern.test(fname);
  }
  function validateAddress(inputValue) {
    // Check if the input contains only letters and digits using a regular expression
    return /^[0-9]*\s*[a-zA-Z0-9\s,.'()\[\]`{|}~-]+$/.test(inputValue);
  }
  function validateSelect(value) {
    return value !== "";
  }






    $(document).ready(function() {
    $('#image_path').on('change', function() {
      const input = this;

      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
          $('#uploaded-image').attr('src', e.target.result);
          $('.image-container').show();
          $('#image_path').addClass('col-md-12');
          $('.image-container').css('display', 'flex');

          // Set the file name in the <p> tag
          const fileName = input.files[0].name;
          $('.file-name').text('File name: ' + fileName);
          $('.file-name').addClass('col-md-12');
        };


        reader.readAsDataURL(input.files[0]);
      } else {
        $('#uploaded-image').attr('src', '../img/buildings/default-image.png');
        $('.image-container').hide();
        $('#image_path').removeClass('col-md-12');
        $('.image-container').css('display', 'none');

        // Clear the file name in the <p> tag
        $('.file-name').empty();
      }
    });
  });


$(document).ready(function() {
  $('#barangay').on('change', function() {
    var selectedBrgyCode = $(this).val();
    var selectedCityCode = $(this).find('option:selected').data('city');
    var selectedProvinceCode = $(this).find('option:selected').data('province');
    var selectedRegionCode = $(this).find('option:selected').data('region');

    if (selectedBrgyCode !== 'none') {
      // Update the City dropdown
      $.post('../includes/address.php', {
        action: 'city_by_brgy',
        filter: selectedBrgyCode
      }, function(data) {
        $('#city').html(data);
        // Select the corresponding City value
        var cityDropdown = $('#city');
        cityDropdown.find('option[value="' + selectedCityCode + '"]').prop('selected', true);
        cityDropdown.trigger('change');

        // Update the Province dropdown
        $.post('../includes/address.php', {
          action: 'province_by_city',
          filter: selectedCityCode
        }, function(data) {
          $('#provinces').html(data);
          // Select the corresponding Province value
          var provinceDropdown = $('#provinces');
          provinceDropdown.find('option[value="' + selectedProvinceCode + '"]').prop('selected', true);
          provinceDropdown.trigger('change');

          // Update the Region dropdown
          $.post('../includes/address.php', {
            action: 'region_by_province',
            filter: selectedProvinceCode
          }, function(data) {
            $('#region').html(data);
            // Select the corresponding Region value
            var regionDropdown = $('#region');
            regionDropdown.find('option[value="' + selectedRegionCode + '"]').prop('selected', true);
          });
        });
      });
    }
  });
});



		const input = document.getElementById('image_path');
		const preview = document.getElementById('image-container');

		input.addEventListener('change', () => {
			preview.innerHTML = '';
			const files = input.files;
			for (let i = 0; i < files.length; i++) {
				const file = files[i];
				createPreviewImage(file);
			}
		});

		function createPreviewImage(file) {
      if (input.files.length > 6) {
        alert("You can upload up to 6 images only.");
        return;
      }
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        // Check if the last row has two images, if yes, create a new row
        const lastRow = preview.lastElementChild;
        if (!lastRow || lastRow.childElementCount >= 3) {
          const newRow = document.createElement('div');
          newRow.classList.add('d-inline-flex', 'g-3', 'justify-content-md-center', 'pl-2', 'pr-2', 'col-auto', 'h-50');
          preview.appendChild(newRow);
        }

        const previewImage = document.createElement('div');
        previewImage.classList.add('uploaded-image', 'col-auto');
        previewImage.innerHTML = `
          <img src="${reader.result}">
          <div class="remove-image" onclick="removeImage(this.parentNode)">X</div>
        `;

        // Append the image to the last row
        preview.lastElementChild.appendChild(previewImage);
      };
    }

    function removeImage(previewImage) {
      const input = document.getElementById('image_path');
      const files = Array.from(input.files);
      const index = Array.from(preview.children).indexOf(previewImage);
      if (index !== -1) {
        files.splice(index, 1);
        const newFileList = new DataTransfer();
        files.forEach(file => newFileList.items.add(file));
        input.files = newFileList.files;
      }
      previewImage.remove();
    }

