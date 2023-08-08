<template>
    <BootstrapModalComponent modalid="cartModal">
        <template #header>
            <h1 class="modal-title fs-5">Cart</h1>
        </template>
        <template #body>
            <cart></cart>
        </template>
        <template #footer>
            <a class="btn btn-primary" role="button" href="/cart">Open cart</a>
        </template>
    </BootstrapModalComponent>

    <!-- <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Cart</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <cart></cart>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div> -->
</template>
<script>
import BootstrapModalComponent from './BootstrapModalComponent.vue';

export default {
    components: {
        BootstrapModalComponent,
    }
}
</script>
