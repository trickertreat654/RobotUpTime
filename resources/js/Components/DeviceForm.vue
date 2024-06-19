<script setup>
import { PlusIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  groups: Array,
  device: Object,
})
const form = useForm({
  name: props.device ? props.device.name : '',
  uri: props.device ? props.device.uri : '',
  port: props.device ? props.device.port : '',
  type: props.device ? props.device.type : '',
  interval: props.device ? props.device.interval : '',
  group_id: props.device ? props.device.group.id : '',
  device_name: props.device ? props.device.device_name : '',
});

const formGroup = useForm({
  name: '',
})

const someEvent = () => {
  if (route().current('devices.create'))
    form.post(route('devices.store'))
  else
    form.put(route('devices.update', props.device.id))
}
const addGroup = ref(false)
</script>


<template>
  <form class="flex flex-col max-w-3xl mx-auto" @submit.prevent="someEvent">
    <label for="name" class="text-black">Name</label>
    <input v-model="form.name" id="name" name="name" type="text" autocomplete="name" />
    <label for="name" class="text-black">Device ID (1 word)</label>
    <input v-model="form.device_name" id="device_name" name="device_name" type="text" />

    <label for="email" class="text-black ">Type of Device</label>
    <input v-model="form.type" id="text" name="text" type="text" autocomplete="text" />

    <label for="username" class="text-black ">URL</label>
    <span class="">http://</span>
    <input v-model="form.uri" type="text" name="text" id="text" autocomplete="url" placeholder="janesmith" />

    <label for="port" class="text-black ">Port</label>
    <input v-model="form.port" type="number" name="port" id="port" autocomplete="given-name" />

      <label for="interval" class="text-black ">Interval</label>
        <select v-model="form.interval" id="timezone" name="timezone"
          class="">
          <option value="1">Every Hour</option>
          <option value="2">Every 2 Hours</option>
          <option value="3">Every 3 Hours</option>
          <option value="6"> Every 6 Hours</option>
          <option value="12"> Every 12 Hours</option>
          <option value="24"> Every Day</option>
        </select>
      <label for="timezone" class="block text-sm font-medium leading-6 text-black">Group
        <button type="button" @click="addGroup = !addGroup">
          <PlusIcon class="inline-block w-5 h-5 text-black" />
        </button>
      </label>
      <Transition>
        <form class="mt-2" v-if="addGroup" @submit.prevent="formGroup.post(route('groups.store'), {
    onSuccess: (page) => {
      formGroup.reset();
      addGroup = false;
    },
  })">
            <label for="name" class="text-black">Name</label>
              <input v-model="formGroup.name" id="name" name="name" type="name" autocomplete="name"
                class="" />
          <button type="submit"
            class="">Save
            New Group</button>
        </form>
      </Transition>
        <select v-model="form.group_id" id="timezone" name="timezone"
          class="">
          <option v-for="group in groups" :key="group.id" :value="group.id">
            {{ group.name }}
          </option>
        </select>
    <div class="flex mt-8">
      <button type="submit"
        class="p-4 bg-blue-300">Save</button>
    </div>
  </form>
</template>

<style scoped>
/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>