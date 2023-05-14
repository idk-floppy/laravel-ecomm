export default function ErrorAlert() {
    return this.$swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong!',
    });
}
