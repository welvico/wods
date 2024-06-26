import os

def read_template(template_file):
    with open(template_file, 'r', encoding='utf-8') as f:
        return f.read()

def replace_ip(template_content, ip_file):
    with open(ip_file, 'r', encoding='utf-8') as f:
        ip_lines = f.readlines()

    replaced_content = template_content
    for line in ip_lines:
        if line.strip():
            parts = line.strip().split(',')
            if len(parts) == 2:
                keyword = parts[0].strip()
                url = parts[1].strip()
                replaced_content = replaced_content.replace('ipipip', url)

    return replaced_content

def main():
    template_dir = 'template'
    ip_dir = 'ip'
    results_dir = 'result'

    # 获取模板文件和IP文件的列表
    template_files = [f for f in os.listdir(template_dir) if os.path.isfile(os.path.join(template_dir, f))]
    ip_files = [f for f in os.listdir(ip_dir) if os.path.isfile(os.path.join(ip_dir, f))]

    for template_file in template_files:
        if template_file.startswith('Fujian_') and template_file.endswith('.txt'):  # 确认模板文件命名规则
            template_path = os.path.join(template_dir, template_file)
            ip_file = os.path.join(ip_dir, template_file.replace('template_', 'ip_'))

            if os.path.exists(ip_file):
                template_content = read_template(template_path)
                replaced_content = replace_ip(template_content, ip_file)

                result_file = os.path.join(results_dir, 'result_' + template_file)
                with open(result_file, 'w', encoding='utf-8') as f:
                    f.write(replaced_content)
            else:
                print(f"Error: IP file {ip_file} not found for template {template_file}")

if __name__ == '__main__':
    main()
