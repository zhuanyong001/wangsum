import modal from '@/components/dy/Modal/modal.js';
import { computed, unref } from 'vue';
import { useSelectStore } from '@/store/selectOpts';
const store = useSelectStore();
export const type_list = computed(() => store.type_list);
export const useEdit = ({ getData }) => {
  const title = '配置项';
  const formConfig = [
    { label: '字段名称', key: 'name', type: 'text' },
    { label: '字段值', key: 'value', type: 'text', desc: '' },
    { label: '描述', key: 'description', type: 'text', desc: '' },
    { label: '字段类型', key: 'type', type: 'select', opts: type_list.value },
    { label: '分类', key: 'category', type: 'text', default: '' },
    { label: '是否私有', key: 'is_private', type: 'switch', default: 1 },
  ];
  const submit = (formData) => {
    let url = '/web3/system_configs/index';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/system_configs/index/${formData.value.id}`;
      method = 'PUT_JSON';
    }
    useHttp(url, method, { ...formData.value })
      .then((res) => {
        if (res.code === 200) {
          message.success('添加成功');
          modal.close();
          getData();
        }
      })
      .catch((err) => {
        console.log('====>>err', err);
        message.error(err.msg);
      });
  };
  const addNew = (form = {}) => {
    modal
      .open({
        props: {
          formConfig,
          title,
          submit,
        },
      })
      .Init(form);
  };
  return { addNew };
};
