require('./bootstrap');

// Import modules...
import {createApp, h, reactive} from 'vue';
import {App as InertiaApp, plugin as InertiaPlugin} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';

import PrimeVue from "primevue/config";

import AutoComplete from 'primevue/autocomplete';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import Avatar from 'primevue/avatar';
import AvatarGroup from 'primevue/avatargroup';
import Badge from 'primevue/badge';
import BadgeDirective from 'primevue/badgedirective';
import Button from 'primevue/button';
import Breadcrumb from 'primevue/breadcrumb';
import Calendar from 'primevue/calendar';
import Card from 'primevue/card';
import Carousel from 'primevue/carousel';
import Checkbox from 'primevue/checkbox';
import Chip from 'primevue/chip';
import Chips from 'primevue/chips';
import ColorPicker from 'primevue/colorpicker';
import Column from 'primevue/column';
import ConfirmationService from 'primevue/confirmationservice';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmPopup from 'primevue/confirmpopup';
import ContextMenu from 'primevue/contextmenu';
import DataTable from 'primevue/datatable';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions';
import Dialog from 'primevue/dialog';
import Divider from 'primevue/divider';
import Dropdown from 'primevue/dropdown';
import Fieldset from 'primevue/fieldset';
import FileUpload from 'primevue/fileupload';
import InlineMessage from 'primevue/inlinemessage';
import Inplace from 'primevue/inplace';
import InputMask from 'primevue/inputmask';
import InputNumber from 'primevue/inputnumber';
import InputSwitch from 'primevue/inputswitch';
import InputText from 'primevue/inputtext';
import Knob from 'primevue/knob';
import Galleria from 'primevue/galleria';
import Listbox from 'primevue/listbox';
import MegaMenu from 'primevue/megamenu';
import Menu from 'primevue/menu';
import Menubar from 'primevue/menubar';
import Message from 'primevue/message';
import MultiSelect from 'primevue/multiselect';
import OrderList from 'primevue/orderlist';
import OrganizationChart from 'primevue/organizationchart';
import OverlayPanel from 'primevue/overlaypanel';
import Paginator from 'primevue/paginator';
import Panel from 'primevue/panel';
import PanelMenu from 'primevue/panelmenu';
import Password from 'primevue/password';
import PickList from 'primevue/picklist';
import ProgressBar from 'primevue/progressbar';
import Rating from 'primevue/rating';
import RadioButton from 'primevue/radiobutton';
import Ripple from 'primevue/ripple';
import SelectButton from 'primevue/selectbutton';
import ScrollPanel from 'primevue/scrollpanel'
import ScrollTop from 'primevue/scrolltop';
import Slider from 'primevue/slider';
import Sidebar from 'primevue/sidebar';
import Skeleton from 'primevue/skeleton';
import SplitButton from 'primevue/splitbutton';
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel';
import Steps from 'primevue/steps';
import TabMenu from 'primevue/tabmenu';
import Tag from 'primevue/tag';
import TieredMenu from 'primevue/tieredmenu';
import Textarea from 'primevue/textarea';
import Timeline from 'primevue/timeline';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import Toolbar from 'primevue/toolbar';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Tooltip from 'primevue/tooltip';
import ToggleButton from 'primevue/togglebutton';
import Tree from 'primevue/tree';
import TreeTable from 'primevue/treetable';
import TriStateCheckbox from 'primevue/tristatecheckbox';

import VueLoading from 'vue-loading-overlay';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

import mitt from 'mitt';

const el = document.getElementById('app');

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({methods: {route}})
    .use(InertiaPlugin)
    .use(PrimeVue, {
        ripple: true,
        locale: {
            accept: '????????????',
            reject: '??????????????????',
            dateFormat: 'dd/mm/yy',
            dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dayNamesMin: ['???.', '???.', '???.', '??????.', '???.', '???.', '???.'],
            monthNames: ["??????????????????", "??????????????????????????????", "??????????????????", "?????????????????????", "?????????????????????", "June", "July", "August", "September", "October", "November", "December"],
            monthNamesShort: ["??????.", "???.???.", "??????.???.", "??????.???", "???.???.", "??????.???", "???.???.", "???.???.", "???.???.", "???.???.", "???.???.", "???.???."],
            today: 'Today',
            weekHeader: 'Wk',
            firstDayOfWeek: 0,
            weak: 'Weak',
            medium: 'Medium',
            strong: 'Strong',
            passwordPrompt: 'Enter a password',
            emptyFilterMessage: 'No results found',
            emptyMessage: 'No available options'
        }
    })
    .use(ConfirmationService)
    .use(ToastService)
    .use(VueLoading, {
        color: 'skyblue'
    })
app.component('loading', VueLoading)
app.config.globalProperties.$appState = reactive({inputStyle: 'outlined'});

app.config.globalProperties.$filters = {
    date(value, utc = false) {
        if (!value) return '-'

        if (utc)
            value += ' UTC'

        return moment(value).format('DD/MM/YYYY')
    },
    datetime(value, utc = false) {
        if (!value) return '-'
        if (utc)
            value += ' UTC'
        return moment(value).format('DD/MM/YYYY HH:mm')
    },
    decimal(value, pre = 0) {
        let format = '0,0.' + _.repeat('0', pre);

        return numeral(value).format(format);
    }
}

app.config.globalProperties.$a = {
    getGoldPrice() {
        return axios.get(route('api.gold-prices.now'))
            .then(({data}) => {
                return data
            })
    },
    getGoldPriceSale() {
        return axios.get(route('api.gold-prices.now'))
            .then(({data}) => {
                return data.gold_price_sale
            })
    },

    // ????????????????????? + ?????????????????? + ????????????
    calcProductSalePrice(product, gold_price_sale) {

        let priceSaleGold = null;
        if (product.sale_with_gold_price) {
            priceSaleGold = numeral(gold_price_sale)
                .add(product.gold_percent.add_sale)
                .multiply(product.gold_percent.percent_sale / 100)
                .multiply(0.0656)
                .multiply(product.wtGram)
                .add(product.tag_wage)
                .value()
        } else {
            priceSaleGold = product.tag_price;
        }

        return Math.floor(priceSaleGold);
    }
}
const emitter = mitt();
app.config.globalProperties.emitter = emitter
app.config.globalProperties.$print = function (data) {
    document.getElementById('printable').innerHTML = data;
    window.print();
}


app.mixin({
    methods: {
        notify(summary, severity = 'success', detail = '', life = 3000) {
            this.$toast.add({severity: severity, summary: summary, detail: detail, life: life})
        }
    }
})

app.directive('tooltip', Tooltip);
app.directive('ripple', Ripple);
// app.directive('code', CodeHighlight);
app.directive('badge', BadgeDirective);

app.component('Accordion', Accordion);
app.component('AccordionTab', AccordionTab);
app.component('AutoComplete', AutoComplete);
app.component('Avatar', Avatar);
app.component('AvatarGroup', AvatarGroup);
app.component('Badge', Badge);
app.component('Breadcrumb', Breadcrumb);
app.component('Button', Button);
app.component('Calendar', Calendar);
app.component('Card', Card);
app.component('Carousel', Carousel);
// app.component('Chart', Chart);
app.component('Checkbox', Checkbox);
app.component('Chip', Chip);
app.component('Chips', Chips);
app.component('ColorPicker', ColorPicker);
app.component('Column', Column);
app.component('ConfirmDialog', ConfirmDialog);
app.component('ConfirmPopup', ConfirmPopup);
app.component('ContextMenu', ContextMenu);
app.component('DataTable', DataTable);
app.component('DataView', DataView);
app.component('DataViewLayoutOptions', DataViewLayoutOptions);
app.component('Dialog', Dialog);
app.component('Divider', Divider);
app.component('Dropdown', Dropdown);
app.component('Fieldset', Fieldset);
app.component('FileUpload', FileUpload);
// app.component('FullCalendar', FullCalendar);
app.component('InlineMessage', InlineMessage);
app.component('Inplace', Inplace);
app.component('InputMask', InputMask);
app.component('InputNumber', InputNumber);
app.component('InputSwitch', InputSwitch);
app.component('InputText', InputText);
app.component('Galleria', Galleria);
app.component('Knob', Knob);
app.component('Listbox', Listbox);
app.component('MegaMenu', MegaMenu);
app.component('Menu', Menu);
app.component('Menubar', Menubar);
app.component('Message', Message);
app.component('MultiSelect', MultiSelect);
app.component('OrderList', OrderList);
app.component('OrganizationChart', OrganizationChart);
app.component('OverlayPanel', OverlayPanel);
app.component('Paginator', Paginator);
app.component('Panel', Panel);
app.component('PanelMenu', PanelMenu);
app.component('Password', Password);
app.component('PickList', PickList);
app.component('ProgressBar', ProgressBar);
app.component('RadioButton', RadioButton);
app.component('Rating', Rating);
app.component('SelectButton', SelectButton);
app.component('ScrollPanel', ScrollPanel);
app.component('ScrollTop', ScrollTop);
app.component('Slider', Slider);
app.component('Sidebar', Sidebar);
app.component('Skeleton', Skeleton);
app.component('SplitButton', SplitButton);
app.component('Splitter', Splitter);
app.component('SplitterPanel', SplitterPanel);
app.component('Steps', Steps);
app.component('TabMenu', TabMenu);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('Tag', Tag);
app.component('Textarea', Textarea);
app.component('TieredMenu', TieredMenu);
app.component('Timeline', Timeline);
app.component('Toast', Toast);
app.component('Toolbar', Toolbar);
app.component('ToggleButton', ToggleButton);
app.component('Tree', Tree);
app.component('TreeTable', TreeTable);
app.component('TriStateCheckbox', TriStateCheckbox);

app.mount(el);

InertiaProgress.init({color: '#4B5563'});
