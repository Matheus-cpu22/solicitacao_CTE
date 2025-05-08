function showModal(content) {
    const modal = document.getElementById("modal");
    const modalContent = document.getElementById("modal-content");
    modalContent.innerHTML = content;
    modal.style.display = "flex";
  }

  function closeModal() {
    document.getElementById("modal").style.display = "none";
  }