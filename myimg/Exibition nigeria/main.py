import os

# Directory containing the .heic files
directory = r'C:\Users\Alok Vish\Downloads\wetransfer_exibition-nigeria_2025-01-08_1002\Exibition nigeria'

# Get a list of all .heic files in the directory
heic_files = [f for f in os.listdir(directory) if f.endswith('.jpeg')]

# Rename each file
for i, filename in enumerate(heic_files, start=1):
    new_filename = f'img_{i}.png'
    old_file = os.path.join(directory, filename)
    new_file = os.path.join(directory, new_filename)
    os.rename(old_file, new_file)
    print(f"Renamed {old_file} to {new_file}")