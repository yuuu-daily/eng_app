import axios from 'axios';
import {createToaster} from '@meforma/vue-toaster';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const toaster = createToaster({});
window.toaster = toaster;

import dayjs from 'dayjs';
import ja from 'dayjs/locale/ja';

dayjs.locale(ja);
window.dayjs = dayjs;
