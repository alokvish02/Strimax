import os
from PIL import Image, UnidentifiedImageError

# Directory containing the .heic files
directory = r'C:\Users\Alok Vish\Desktop\Sterimax\myimg\Riyadh'

# Get a list of all .heic files in the directory
heic_files = [f for f in os.listdir(directory) if f.endswith('.JPG')]

# Resize and crop each file
for i, filename in enumerate(heic_files, start=1):
    new_filename = f'imgs_{i}.jpg'
    old_file = os.path.join(directory, filename)
    new_file = os.path.join(directory, new_filename)
    
    try:
        # Open the image
        with Image.open(old_file) as img:
            # Convert image to RGB (to ensure compatibility)
            img = img.convert('RGB')
            
            # Resize the image
            img = img.resize((800, 800))  # Resize to 800x800 pixels
            
            # Crop the image
            left = 100
            top = 100
            right = 700
            bottom = 700
            img = img.crop((left, top, right, bottom))
            
            # Save the edited image in JPEG format
            img.save(new_file, format='JPEG')
        
        print(f"Processed {old_file} and saved as {new_file}")
    except UnidentifiedImageError:
        print(f"Cannot identify image file {old_file}, skipping.")
    except Exception as e:
        print(f"An error occurred while processing {old_file}: {e}")