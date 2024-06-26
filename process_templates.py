import os
import yaml

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
                replaced_content = replaced_content.replace('ipipip', url, 1)  # Replace only the first occurrence

    return replaced_content

def merge_content(results_dir):
    result_files = [f for f in os.listdir(results_dir) if f.startswith('result_') and f.endswith('.txt')]

    merged_content = {}
    for result_file in result_files:
        genre = None
        with open(os.path.join(results_dir, result_file), 'r', encoding='utf-8') as f:
            for line in f:
                if line.strip():
                    if line.startswith('#genre#'):
                        genre = line.strip()
                        if genre not in merged_content:
                            merged_content[genre] = []
                    else:
                        if genre:
                            merged_content[genre].append(line.strip())

    return merged_content

def write_merged_content(merged_content, output_file):
    with open(output_file, 'w', encoding='utf-8') as f:
        for genre, lines in merged_content.items():
            f.write(genre + '\n')
            for line in lines:
                f.write(line + '\n')
            f.write('\n')

def main(config_file):
    with open(config_file, 'r', encoding='utf-8') as f:
        config = yaml.safe_load(f)

    template_dir = config['template_dir']
    ip_dir = config['ip_dir']
    results_dir = config['results_dir']
    output_file = config['output_file']

    template_files = os.listdir(template_dir)
    for template_file in template_files:
        if template_file.endswith('.txt'):
            template_path = os.path.join(template_dir, template_file)
            ip_file = os.path.join(ip_dir, template_file.replace('template_', 'ip_'))

            template_content = read_template(template_path)
            replaced_content = replace_ip(template_content, ip_file)

            result_file = os.path.join(results_dir, 'result_' + template_file)
            with open(result_file, 'w', encoding='utf-8') as f:
                f.write(replaced_content)

    merged_content = merge_content(results_dir)
    write_merged_content(merged_content, output_file)

if __name__ == '__main__':
    main('config.yml')
