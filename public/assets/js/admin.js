/**
 * DaNang PC - Admin Back-Office JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Dynamic Product Specifications
  const specsTableBody = document.getElementById('specs-table-body');
  const addSpecBtn = document.getElementById('btn-add-spec');

  if (specsTableBody && addSpecBtn) {
    addSpecBtn.addEventListener('click', function () {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="text" name="spec_names[]" class="form-control" placeholder="Tên thông số (VD: Socket, Tần số quét...)" required></td>
        <td><input type="text" name="spec_values[]" class="form-control" placeholder="Giá trị (VD: LGA1700, 165Hz...)" required></td>
        <td style="text-align: center;">
          <button type="button" class="btn btn-sm btn-danger btn-remove-spec"><i class="fa-solid fa-trash"></i></button>
        </td>
      `;
      specsTableBody.appendChild(tr);
    });

    specsTableBody.addEventListener('click', function (e) {
      if (e.target.closest('.btn-remove-spec')) {
        const row = e.target.closest('tr');
        if (row) row.remove();
      }
    });
  }

  // 2. Image File Preview
  const imageInput = document.querySelector('input[type="file"][accept*="image"]');
  const imagePreview = document.getElementById('image-preview');

  if (imageInput && imagePreview) {
    imageInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // 3. Confirm Delete Prompts
  document.querySelectorAll('form.form-delete').forEach(form => {
    form.addEventListener('submit', function (e) {
      const msg = this.dataset.confirm || 'Bạn có chắc chắn muốn xóa mục này không? Hành động này không thể hoàn tác!';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });
});
