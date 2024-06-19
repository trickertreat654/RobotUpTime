<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ListTable from '@/Components/ListTable.vue'
import EventLog from '@/Components/ListTable.vue'
import DeviceForm from '@/Components/DeviceForm.vue'
import { useInfiniteScroll } from '@/Composables/useInfiniteScroll.js'
import { useIntersect } from '@/Composables/useIntersect.js'
import { useSearch } from '@/Composables/useSearch.js'
import { usePage, Link } from '@inertiajs/vue3'


const props = defineProps({
  device: Object,
  checks: Object,
  filters: Object,
  count: Number,
  devicesDown: Number,
  groups: Object,
  eventLogs: Object,
})

console.log(props.eventLogs.data)


const page = usePage()

const currentTab = ref('ListTable')

const tabs = {
  ListTable,
  EventLog,
  DeviceForm,
}


const dynamicProps = computed(() => {
  if(currentTab.value === 'ListTable')
  return {
  status: props.check,
  device: props.device,
  items: items.value,
  groups: props.groups,
  count: props.count,
  devicesDown: props.devicesDown,
  tableName: 'Filler',
  tableTypeA: 'Filler',
  tableTypeB: 'Filler',
  tableStatus: 'Filler',
  tableNumber: 'Filler',
  tableTime: 'Filler',
  statsStatus: '1',
  statsDevice: props.device.name,
  statsLocation: 'Filler',
  statsATitle: 'Number of Pings',
  statsAValue: items.value.length,
  statsBTitle: 'Filler',
  statsBValue: 5,
  statsCTitle: 'Filler',
  statsCValue: 5,
  statsDTitle: 'Filler',
  statsDValue: 5,
}

  else if(currentTab.value === 'EventLog')
  {
  
  return {
    status: props.check,
    device: props.device,
    items: props.eventLogs.data,
    groups: props.groups,
    count: props.count,
    devicesDown: props.devicesDown,
    tableName: 'Filler',
    tableTypeA: 'Filler',
    tableTypeB: 'Filler',
    tableStatus: 'Filler',
    tableNumber: 'Filler',
    tableTime: 'Filler',
    statsStatus: '1',
    statsDevice: props.device.name,
    statsLocation: 'Filler',
    statsATitle: 'Number of Pings',
    statsAValue: items.value.length,
    statsBTitle: 'Filler',
    statsBValue: 5,
    statsCTitle: 'Filler',
    statsCValue: 5,
    statsDTitle: 'Filler',
    statsDValue: 5,
  }
}
  else if(currentTab.value === 'DeviceForm')  
  return {
    status: props.check,
    device: props.device,
    items: items.value,
    groups: props.groups,
    count: props.count,
    devicesDown: props.devicesDown,
    tableName: 'Filler',
    tableTypeA: 'Filler',
    tableTypeB: 'Filler',
    tableStatus: 'Filler',
    tableNumber: 'Filler',
    tableTime: 'Filler',
    statsStatus: '1',
    statsDevice: props.device.name,
    statsLocation: 'Filler',
    statsATitle: 'Number of Pings',
    statsAValue: items.value.length,
    statsBTitle: 'Filler',
    statsBValue: 5,
    statsCTitle: 'Filler',
    statsCValue: 5,
    statsDTitle: 'Filler',
    statsDValue: 5,
  }
});



const { items, loadMoreItems, initialUrl, reset, canLoadMoreItems } = useInfiniteScroll('checks')

const landmark = ref(null);

useIntersect(landmark, loadMoreItems, { rootMargin: '0px', })

const { search } = useSearch(initialUrl, reset, 'devices.show', { device: page.props.device.id })



</script>
<template>
  <AuthenticatedLayout v-model="search">
    <main class="mx-auto max-w-7xl">
            <button v-for="(_, tab) in tabs" :key="tab" :class="currentTab === tab ? 'tab-button text-custom-blue' : ''"
              @click="currentTab = tab">
              {{ tab ==='ListTable' ? 'Pings for '+ props.device.name :
              tab === 'DeviceForm' ? 'Edit Device' :
              tab === 'Substations' ? 'Substations' : 'Event Logs'}}
            </button>
            <Link :href="route('ping', props.device)" method="post">Ping</Link>
            <a :href="'http://' + props.device.uri + ':' + props.device.port" target="_blank" >Vist</a>
      <Transition @after-enter=""  name="fade" mode="out-in">
        <component :is="tabs[currentTab]" :key="currentTab" v-bind=dynamicProps>
        </component>
      </Transition>
        <!-- <component :is="tabs[currentTab]" :key="currentTab" :status="props.check" :device="props.device" :items="items"
          :groups="props.groups" :count="props.count" :devicesDown="props.devicesDown" tableName="Filler"
          tableTypeA="Filler" tableTypeB="Filler" tableStatus="Filler" tableNumber="Filler" tableTime="Filler"
          statsStatus="1" :statsDevice="props.device.name" statsLocation="Filler" statsATitle="Number of Pings" :statsAValue=items.length
          statsBTitle="Filler" :statsBValue=5 statsCTitle="Filler" :statsCValue=5 statsDTitle="Filler" :statsDValue=5>
        </component>
       -->
      

        <div class="flex justify-center w-full " ref="landmark">
          <Transition  name="fade" mode="out-in">
            <span v-if="!canLoadMoreItems && currentTab === 'ListTable'" class="pt-2 pb-8 text-sm text-white"> End of
              content</span>
            <span v-else class="pt-2 pb-8 text-sm text-white"> </span>
            </Transition>
          </div>
    </main>
  </AuthenticatedLayout>
</template>


<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

</style>
  

