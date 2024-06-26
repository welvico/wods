import os

def replace_ip_in_template(ip_file, template_file, result_file):
    with open(ip_file, 'r') as ip_f, open(template_file, 'r') as template_f:
        ip_lines = ip_f.readlines()
        template_content = template_f.read()

    replaced_content = template_content
    for line in ip_lines:
        ip_port = line.strip()
        replaced_content = replaced_content.replace('ipipip', ip_port, 1)

    with open(result_file, 'w') as result_f:
        result_f.write(replaced_content)

def main():
    # Paths to your files
    ip_fujian = 'ip/Fujian_114.txt'
    template_fujian = 'template/Fujian_114.txt'
    result_fujian = 'result/Fujian_114.txt'

    ip_henan = 'ip/Henan_327.txt'
    template_henan = 'template/Henan_327.txt'
    result_henan = 'result/Henan_327.txt'

    # Process Fujian files
    replace_ip_in_template(ip_fujian, template_fujian, result_fujian)

    # Process Henan files
    replace_ip_in_template(ip_henan, template_henan, result_henan)

if __name__ == "__main__":
    main()
