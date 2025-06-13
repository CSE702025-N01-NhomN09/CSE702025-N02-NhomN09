function showForm(id) {
  document.querySelectorAll(".form-overlay").forEach(f => f.style.display = "none");
  document.getElementById(id).style.display = "block";
}

function setGia(formId) {
  const form = document.getElementById(formId);
  const select = form.querySelector("select[name='tendv']");
  const hidden = form.querySelector("input[type='hidden']");
  const value = select.value;

  if (formId === 'guiXeForm') {
    if (value.includes("đạp")) hidden.value = 20000;
    else if (value.includes("máy")) hidden.value = 40000;
    else if (value.includes("ô tô")) hidden.value = 2000000;

  } else if (formId === 'giatDoForm') {
    const kg = parseInt(document.getElementById('soKg').value);
    if (isNaN(kg) || kg <= 0) {
      alert("Vui lòng nhập số kg hợp lệ!");
      return false;
    }
    if (value.includes("thường")) hidden.value = 10000 * kg;
    else if (value.includes("nặng")) hidden.value = 25000 * kg;

  } else if (formId === 'veSinhForm') {
    if (value.includes("cơ bản")) hidden.value = 200000;
    else if (value.includes("đầy đủ")) hidden.value = 250000;
  }

  return true;
}
