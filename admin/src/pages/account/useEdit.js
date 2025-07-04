import modal from '@/components/dy/Modal/modal.js';
import {computed,unref,reactive} from 'vue'
import { useSelectStore } from '@/store/selectOpts'

const store = useSelectStore()
export const roleList = computed(() => store.role_list)


export const useEdit = ({ getData }) => {
  const title = '账号';
  const formConfig = reactive([
    { label: '角色 ID', key: 'role_id', type: 'select', desc: '关联角色' ,opts: roleList},
    { label: '是否超级账号', key: 'is_super', type: 'switch', desc: '是否是超级账号',default:1 },
    { label: '用户账号', key: 'user_account', type: 'text', desc: '用户账号' },
    { label: '用户名', key: 'username', type: 'text', desc: '填写账号地址' },
    { label: '密码', key: 'password', type: 'text', desc: '用户密码' }
  ]);
  const submit = (formData) => {
    let url = '/web3/roles/save_admin/0';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/roles/save_admin/${formData.value.id}`;
      method = 'POST_JSON';
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
