<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ListTable from '@/Components/ListTable.vue'
import { useInfiniteScroll } from '@/Composables/useInfiniteScroll.js'
import { useIntersect } from '@/Composables/useIntersect.js'
import { useSearch } from '@/Composables/useSearch.js'
import { watch, onUnmounted } from 'vue' 
import { usePage, router }  from '@inertiajs/vue3'


const props = defineProps({
  devices: Object,
  filters: Object,
  count: Number,
  devicesDown: Number,

})

// onUnmounted(() => {
//   window.history.pushState({}, '', '/device');
// })


const showDown = ref(false)

const showDownDevices =  (all) => {
  console.log('showDownDevices')
  showDown.value = all
  router.get(route('devices.index', {status: all}), {}, {
        preserveState: false,
        preserveScroll: true,
        // onSuccess: () => {
        //   window.history.replaceState({}, '', '/devices');
        //   // callback();  
        // }
      });
  
}


const page = usePage()

console.log(page.url)

const tabs = {
  ListTable,
}

console.log()

const currentTab = ref('ListTable')

const { items, loadMoreItems, initialUrl, reset, canLoadMoreItems } = useInfiniteScroll('devices')

const landmark = ref(null);

useIntersect(landmark, loadMoreItems, { rootMargin: '0px', })

const { search } = useSearch(initialUrl, reset)




</script>
<template>
  <AuthenticatedLayout v-model="search">
    <main class="mx-auto max-w-7xl">
     
          
            <button class="tab-button text-custom-blue"  @click="showDownDevices(false)">All Devices</button>
            <button class="tab-button text-custom-blue" @click="showDownDevices(true)">Offline Only</button>
          
      <Transition name="fade" mode="out-in">
        <component 
        :is="tabs[currentTab]" 
        :key="currentTab" 
        :status="props.check" 
        :device="props.device" 
        :items="items"
        :groups="props.groups" 
        :count="props.count" 
        :devicesDown="props.devicesDown" 
        tableName="Filler"
        tableTypeA="Filler" 
        tableTypeB="Filler" 
        tableStatus="Filler" 
        tableNumber="Filler" 
        tableTime="Filler"
        statsStatus="1" 
        statsDevice="Monitoring Server" 
        statsLocation="Local Office" 
        statsATitle="Number of Devices"
        :statsAValue=props.count 
        statsBTitle="Down Devices" 
        :statsBValue=props.devicesDown 
        statsCTitle="Filler" 
        :statsCValue=5
        statsDTitle="Filler" 
        :statsDValue=5>
        </component>
      </Transition>
      <div class="flex justify-center w-full " ref="landmark">
        <span v-if="!canLoadMoreItems && currentTab === 'ListTable'" class="pt-2 pb-8 text-sm text-white">End of
          content</span>
      </div>
    </main>
  </AuthenticatedLayout>
</template>