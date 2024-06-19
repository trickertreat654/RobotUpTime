import { ref, watch, computed } from 'vue';
import { router } from '@inertiajs/vue3';


export function useSearch(initialUrl, callback, routeName = 'devices.index', additionalParams = {}) {

    
const search = ref('')  

watch(() => search.value, (search) => {
    console.log('Search:', search);
    if (search.length > 2 || search.length === 0) {
      const params = { ...additionalParams };
      if (search.length > 2) params.search = search;

      console.log('Params:', params);

      router.get(route(routeName, params), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          window.history.replaceState({}, '', initialUrl);
          callback();  
        }
      });
    }
  });

return {search}

}