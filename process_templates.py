import os
import re

template_dir = 'template'
result_dir = 'result'

# 1. 替换模板中的占位符
def replace_template(filename):

    template_file = os.path.join(template_dir, filename) 
    content_file = filename  

    with open(template_file, 'r') as f:
        template = f.read()
        
    with open(os.path.join('ip', content_file), 'r') as f:
        content = f.read()

    domain_port = re.findall(r'http://\S+', content)  
    for dp in domain_port:
        template = template.replace('ipipip', dp, 1)

    result_file = os.path.join(result_dir, content_file)
    with open(result_file, 'w') as f:
        f.write(template)
        
# 2. 合并相同分组        
def merge_by_group(genre):

    result = ''
    for root, dirs, files in os.walk(result_dir):
        for file in files:
            if file.startswith(genre):
                with open(os.path.join(root, file), 'r') as f:  
                    result += f.read() + '\n'
                    
    result_file = os.path.join(result_dir, 'all.txt')            
    with open(result_file, 'a') as f:
        f.write(genre + '#genre#\n') 
        f.write(result)
        
# 3. 主函数
def main():

  for filename in os.listdir(template_dir):
    
    if filename.endswith(".txt"):  

      replace_template(filename)

  genres = ['CCTV', '山东卫视', '快乐垂钓']

  for genre in genres:
    merge_by_group(genre)
    
if __name__ == '__main__':
  main()
