<style>
    .fade-in {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-card {
        border-radius: 1.5rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
        overflow: hidden;
    }

    .header-gradient {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .header-gradient-warning {
        background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%);
    }

    .modern-input {
        border-radius: 0.75rem;
        border: 1.5px solid #e2e8f0;
        padding: 0.6rem 1rem;
        transition: 0.2s;
    }

    .modern-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        outline: none;
    }

    .drop-zone {
        cursor: pointer;
        border: 2px dashed #cbd5e1;
        transition: 0.3s;
    }

    .drop-zone:hover {
        border-color: #4e73df;
        background-color: #f1f5f9 !important;
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }
</style>

<script>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const fileNameText = document.getElementById('file-name');

    dropArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameText.innerHTML = `<i class="fas fa-check-circle text-success"></i> ${this.files[0].name.substring(0, 15)}...`;
            dropArea.style.borderColor = '#10b981';
        }
    });

    ['dragover', 'dragleave', 'drop'].forEach(name => {
        dropArea.addEventListener(name, (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    dropArea.addEventListener('drop', (e) => {
        fileInput.files = e.dataTransfer.files;
        fileNameText.innerHTML = `<i class="fas fa-check-circle text-success"></i> ${e.dataTransfer.files[0].name.substring(0, 15)}...`;
    });
</script>