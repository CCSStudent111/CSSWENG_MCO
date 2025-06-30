<template>
    <ul>
        <li v-for="type in documentTypes" :key="type.id">
            {{ type.name }}
        </li>
    </ul>
</template>

<script setup>
const props = defineProps({
    documentTypes: Array
})
</script>