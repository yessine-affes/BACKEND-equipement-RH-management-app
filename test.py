import os

# Create directories if they don't exist
os.makedirs('storage/app', exist_ok=True)

# Define the paths for all required XML files
xml_files = [
    'storage/app/admins.xml',
    'storage/app/employees.xml',
    'storage/app/equipments.xml',
    'storage/app/projects.xml',
    'storage/app/reports.xml',
    'storage/app/tasks.xml',
    'storage/app/task_assignments.xml',
    'storage/app/certifications.xml',
    'storage/app/users.xml'
]

# Create each XML file if it does not exist
for file_path in xml_files:
    if not os.path.exists(file_path):
        with open(file_path, 'w') as f:
            f.write('<?xml version="1.0" encoding="UTF-8"?>\n<data></data>')

# Confirm creation of files
print('XML files have been created if they did not exist.')
