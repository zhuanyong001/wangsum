import fs from 'fs';
import path from 'path';
import archiver from 'archiver';
import { fileURLToPath } from 'url';

// 获取当前文件的目录路径
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const distDir = path.resolve(__dirname, './dist');
const zipFilePath = path.resolve(__dirname, './dist.zip');

// 创建文件流，将打包后的文件压缩为 ZIP 格式
const output = fs.createWriteStream(zipFilePath);
const archive = archiver('zip', {
  zlib: { level: 9 } // 设置压缩等级
});

output.on('close', () => {
  console.log(`压缩完成，总共 ${archive.pointer()} 字节`);
});

archive.on('error', (err) => {
  throw err;
});

// 将压缩内容写入流
archive.pipe(output);

// 过滤 dist 目录中的文件，排除 video 文件
archive.glob('**/*', {
  cwd: distDir,
  ignore: ['**/video/**'] // 排除 dist 目录中的 video 文件夹或视频文件
});

// 完成压缩
archive.finalize();
