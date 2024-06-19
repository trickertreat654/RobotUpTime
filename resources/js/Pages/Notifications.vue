<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';


const props = defineProps({
  recipients: Array,
})

console.log(props.recipients)

const form = useForm({
  email: '',

});


</script>
<template>
  <AuthenticatedLayout>
    <main class="mx-auto max-w-7xl">
              <button class="text-custom-blue">Notifications</button>

<form @submit.prevent="form.post(route('notifications.store'), {
            onSuccess: () => form.reset(),
          })" class="md:col-span-2">
                <label for="name" class="text-black">Email</label>
                  <input v-model="form.email" id="email" name="email" type="email" autocomplete="email"
                    class="" />
              <button type="submit"
                class="">Save</button>
          </form>
          <li v-for="person in props.recipients" :key="person.email" class="">
                <p class="text-sm font-semibold leading-6 text-black">No Names</p>
                <p class="mt-1 text-xs leading-5 text-gray-400 truncate">{{ person.email }}</p>
              <p class="text-sm leading-6 text-black">{{ person.role }}</p>
          </li>

    </main>

  </AuthenticatedLayout>
</template>