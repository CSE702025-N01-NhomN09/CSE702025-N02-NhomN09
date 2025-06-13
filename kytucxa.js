// Hiện form 
function showSection(id) {
      const sections = document.querySelectorAll('.section');
      sections.forEach(section => {
        section.classList.remove('active');
      });
      document.getElementById(id).classList.add('active');
}

function showForm(id) {
  document.querySelectorAll(".form-overlay").forEach(f => f.style.display = "none");
  document.getElementById(id).style.display = "block";
}

// Dịch vụ
function setGia(formId) {
  const form = document.getElementById(formId);
  const select = form.querySelector("select[name='tendv']");
  const hidden = form.querySelector("input[type='hidden']");
  const value = select.value;

  if (formId === 'GuiXeForm') {
    if (value.includes("Gửi xe đạp")) hidden.value = 20000;
    else if (value.includes("Gửi xe máy")) hidden.value = 40000;
    else if (value.includes("Gửi ô tô")) hidden.value = 2000000;

  } else if (formId === 'GiatDoForm') {
    const kg = parseInt(document.getElementById('soKg').value);
    if (isNaN(kg) || kg <= 0) {
      alert("Vui lòng nhập số kg hợp lệ!");
      return false;
    }
    if (value.includes("Giặt đồ thường")) hidden.value = 10000 * kg;
    else if (value.includes("Giặt đồ nặng")) hidden.value = 25000 * kg;

  } else if (formId === 'VeSinhForm') {
    if (value.includes("Vệ sinh cơ bản")) hidden.value = 200000;
    else if (value.includes("Vệ sinh đầy đủ")) hidden.value = 250000;
  }

  return true;
}

// Phản ánh
let stt = 1;

document.getElementById('request_form').addEventListener('submit', function(e) {
  e.preventDefault();

  const content = document.getElementById('content').value;
  const formData = new FormData();
  formData.append('content', content);

  fetch('kytucxa.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(result => {
    if (result.success) {
      const maphananh = result.maphananh || result.Maphananh;
      const ngaygui = new Date().toISOString().split('T')[0];

      const table = document.getElementById('request_table').querySelector('tbody');
      const row = table.insertRow();
      row.innerHTML = `
        <td>${stt++}</td>
        <td>${content}</td>
        <td>${ngaygui}</td>
        <td>Chờ xử lý</td>
        <td>${maphananh}</td>
      `;
      document.getElementById('request_form').reset();
    } else {
      alert("Lỗi khi lưu: " + result.error);
    }
  })
  .catch(error => {
    console.error(error);
    alert("Gửi phản ánh thất bại.");
  });
});

