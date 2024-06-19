<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import DeviceForm from '@/Components/DeviceForm.vue';

const props = defineProps({
    groups: Array,
})

const form = useForm({
   name: '',
   uri: '',
   port:'',
   type:'',
   interval:'',
   group_id:'',
   device_name:'',
});

const secondaryNavigation = [
  { name: 'Device', href: '#', current: true },
] 



</script>

<template>
    <AuthenticatedLayout>
        <main>
            <h1 class="sr-only">Account Settings</h1>
    
            <header class="border-b border-white/5">
              <!-- Secondary navigation -->
              <nav class="flex py-4 overflow-x-auto">
                <ul role="list" class="flex flex-none min-w-full px-4 text-sm font-semibold leading-6 text-gray-400 gap-x-6 sm:px-6 lg:px-8">
                  <li v-for="item in secondaryNavigation" :key="item.name">
                    <button :class="item.current ? 'text-custom-blue' : ''">New Device</button>
                  </li>
                </ul>
              </nav>
            </header>
    
            <!-- Settings forms -->
           <DeviceForm 
           :name="form.name"
           :device_name="form.device_name"
            :uri="form.uri"
            :port="form.port"
            :type="form.type"
            :interval="form.interval"
            :group_id="form.group_id"
            @some-event="form.post(route('devices.store'))"
           :groups="props.groups"  />
          </main>
    </AuthenticatedLayout>
      </template>